<?php

namespace framework\libraries\owo\classes\Caches;

use framework\libraries\owo\classes\Helpers\OwoHelperPath;
use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;
use framework\libraries\owo\classes\Caches\OwoCacheManager;

use framework\libraries\owo\interfaces\Caches\OwoCacheDirectoryInterface;
use framework\libraries\owo\interfaces\Caches\OwoCacheEntityInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerModeTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringDirTrait;


class OwoCacheDirectory extends OwoCacheManager implements OwoCacheDirectoryInterface
{
    use OwoMakeCommonThrowerTrait;

    use OwoTakeStringDirTrait;
    use OwoTakeIntegerModeTrait;

    public const CACHE_REPOSITORY_DEFAULT_MODE = 0777;

    public function __construct(string $dir = '.', int $duration = 3600, bool $encrypt = true)
    {
        parent::__construct($duration, $encrypt);
        $this->setMode(static::CACHE_REPOSITORY_DEFAULT_MODE);
        $this->setDir($dir);
    }

    public function approves(string $file): bool
    {
        $lifemtime = OwoHelperBackrest::lifemtime($file);
        if (true !== \is_null($lifemtime)) return ($lifemtime <= $this->duration);
        return false;
    }

    public function exists(string $name, string &$path = null): bool
    {
        return (true === \file_exists($path = $this->filepath($name)));
    }

    public function filepath(string $name): string
    {
        return $this->getPath($this->prepareName($name));
    }

    protected function getCaches(): array
    {
        $this->ensureMountedRepository();
        $caches = static::createListCachesFromDir($this->dir, '*');
        return $caches;
    }

    protected function saveCache(string $name, OwoCacheEntityInterface $cache): self
    {
        $path = $this->ensureMountedRepository()->getPath($name);
        if (true !== OwoHelperBackrest::writeContents($path, $cache->getContent())) {
            static::throwRuntimeException(\sprintf('Write File [%s] Contents Failed', $path));
        }
        return $this;
    }

    protected function getCache(string $name, $default = null): ?OwoCacheEntityInterface
    {
        if (true === $this->existsPath($name, $path)) return static::createCacheFromFile($path);
        return null;
    }

    protected function removeCache(string $name): self
    {
        if (true === $this->existsPath($name, $path)) \unlink($path);
        return $this;
    }

    protected function existsPath(string $name, string &$path = null): bool
    {
        return (true === \file_exists($path = $this->getPath($name)));
    }

    protected function getPath(string $name): string
    {
        return $this->dir.'/'.$name;
    }

    protected function ensureMountedRepository(): self
    {
        if (true !== $this->assuresMountingRepository()) {
            static::throwRuntimeException(\sprintf('Directory [%s] Not Found', $this->dir));
        }
        return $this;
    }

    protected function assuresMountingRepository(): bool
    {
        if (true !== \is_dir($this->dir)) {
            return (true === OwoHelperBackrest::makedir($this->dir, $this->mode));
        }
        return true;
    }

    public static function createListCachesFromDir(string $dir, string $pattern = '*'): array
    {
        if (true === \is_dir($dir)) {
            $files = \array_filter(\glob($dir.'/'.$pattern) ?: [], '\\is_file');
            return static::createListCachesFromFiles($files);
        }
        return [];
    }

    public static function createListCachesFromFiles(array $files): array
    {
        $caches = [];
        foreach ($files as $file) {
            $cache = static::createCacheFromFile($file);
            $caches[$cache->getName()] = $cache;
        }
        return $caches;
    }

    public static function createCacheFromFile(string $file): OwoCacheEntityInterface
    {
        $contents = OwoHelperBackrest::loadContents($file);
        if (true === \is_null($contents)) {
            $message = \sprintf('Load File [%s] Contents Failed', $file);
            static::throwRuntimeException($message);
        }

        if (false === ($mtime = \filemtime($file))) {
            $message = \sprintf('Get File [%s] Mtime Failed', $file);
            static::throwRuntimeException($message);
        }
        return static::createCache(OwoHelperPath::filename($file), $contents, $mtime);
    }
}
