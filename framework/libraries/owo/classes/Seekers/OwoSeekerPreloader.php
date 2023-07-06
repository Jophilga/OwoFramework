<?php

namespace framework\libraries\owo\classes\Seekers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Seekers\OwoSeekerPreloaderInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use framework\libraries\owo\traits\Takes\Booleans\OwoTakeBooleanThrowTrait;
use framework\libraries\owo\traits\Takes\Arrays\OwoTakeArrayStringIgnoresTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPatternTrait;


class OwoSeekerPreloader implements OwoSeekerPreloaderInterface
{
    use OwoMakeCommonThrowerTrait;

    use OwoTakeBooleanThrowTrait;
    use OwoTakeArrayStringIgnoresTrait;
    use OwoTakeStringPatternTrait;

    public const SEEK_PRELOADER_PATTERN_PHP = '/(?<filename>.+)\.php$/';

    protected $paths = [];

    public function __construct(string $pattern, array $ignores = [], bool $throw = true)
    {
        $this->setPattern($pattern)->setIgnores($ignores)->setThrow($throw);
    }

    public function preloadPaths(array $paths): self
    {
        foreach ($paths as $path) $this->preloadPath($path);
        return $this;
    }

    public function preloadPath(string $path): self
    {
        $path = \rtrim($path, '/');
        if (true === \is_dir($path)) return $this->preloadDir($path);
        return $this->preloadFile($path);
    }

    public function shouldPreload(string $path, array &$matches = null): bool
    {
        if (true !== $this->assumes($path, $matches)) return false;
        return (true !== $this->shouldIgnore($path));
    }

    public function shouldIgnore(string $path): bool
    {
        foreach ($this->ignores as $ignore) {
            if (0 === \strpos($path, $ignore)) return true;
        }
        return false;
    }

    public function assumes(string $path, array &$matches = null): bool
    {
        return (true === OwoHelperString::matches($this->pattern, $path, $matches));
    }

    public function addIgnore(string $ignore): self
    {
        if (true !== $this->hasIgnore($ignore)) $this->ignores[] = $ignore;
        return $this;
    }

    public function getPreloadedPaths(): array
    {
        return $this->paths;
    }

    public function getPreloadedPath(string $name, $default = null): ?string
    {
        return $this->paths[$name] ?? $default;
    }

    protected function handlePathNotPreloaded(string $path): self
    {
        if (true === $this->throw) {
            static::throwRuntimeException(sprintf('Path [%s] Not Preloaded', $path));
        }
        return $this;
    }

    protected function loadPath(string $path, string $name = null): self
    {
        include_once $path;
        $this->paths[$name ?? $path] = $path;
        return $this;
    }

    protected function preloadFiles(array $files): self
    {
        foreach ($files as $file) $this->preloadFile($file);
        return $this;
    }

    protected function preloadFile(string $file): self
    {
        if (true === $this->shouldPreload($file, $matches)) {
            $this->loadPath($file, $matches['filename'] ?? $file);
        }
        return $this;
    }

    protected function preloadDirs(array $dirs): self
    {
        foreach ($dirs as $dir) $this->preloadDir($dir);
        return $this;
    }

    protected function preloadDir(string $dir): self
    {
        if (false !== ($dirhandle = \opendir($dir))) {
            while (false !== ($path = \readdir($dirhandle))) {
                if (true === \in_array($path, ['.', '..'])) continue;
                $this->preloadPath($dir.'/'.$path);
            }
            \closedir($dirhandle);
        }
        return $this;
    }
}
