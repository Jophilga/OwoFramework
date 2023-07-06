<?php

namespace framework\libraries\owo\classes\Caches;

use framework\libraries\owo\classes\Helpers\OwoHelperPath;
use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;
use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Caches\OwoCacheEntity;

use framework\libraries\owo\interfaces\Caches\OwoCacheManagerInterface;
use framework\libraries\owo\interfaces\Caches\OwoCacheEntityInterface;

use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerDurationTrait;
use framework\libraries\owo\traits\Takes\Booleans\OwoTakeBooleanEncryptTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


abstract class OwoCacheManager implements OwoCacheManagerInterface
{
    use OwoTakeIntegerDurationTrait;
    use OwoTakeBooleanEncryptTrait;

    use OwoMakeCommonThrowerTrait;

    public function __construct(int $duration = 3600, bool $encrypt = true)
    {
        $this->setDuration($duration)->setEncrypt($encrypt);
    }

    public function start(): bool
    {
        return (true === \ob_start());
    }

    public function finish(string $name, bool $overwrite = true): bool
    {
        return (true === $this->digest($name, function ($cache) {
            return \ob_get_clean();
        }, $overwrite));
    }

    public function digest(string $name, callable $callback, bool $overwrite = true): bool
    {
        $content = \call_user_func($callback, $this);
        if (true === \is_string($content)) {
            return (true === $this->write($name, $content, $overwrite));
        }
        return false;
    }

    public function write(string $name, string $content, bool $overwrite = true): bool
    {
        return (true === $this->add(static::createCache($name, $content, \time()), $overwrite));
    }

    public function add(OwoCacheEntityInterface $cache, bool $overwrite = true): bool
    {
        if (true === $this->usable($cache)) {
            $name = $cache->getName();
            if ((true === $overwrite) || (true !== $this->available($name))) {
                $this->saveCache($this->prepareName($name), $cache);
                return true;
            }
        }
        return false;
    }

    public function get(string $name, callable $callback = null): ?OwoCacheEntityInterface
    {
        if (true === $this->available($name, $cache)) return $cache;
        elseif ((true !== \is_null($callback)) && (true === $this->digest($name, $callback))) {
            return $this->getCache($this->prepareName($name));
        }
        return null;
    }

    public function deleteIfObsolete(string $name): self
    {
        if (true !== $this->available($name, $cache)) {
            if (true !== \is_null($cache)) $this->delete($name);
        }
        return $this;
    }

    public function delete(string $name): self
    {
        $this->removeCache($this->prepareName($name));
        return $this;
    }

    public function deleteObsoletes(string $pattern = '.*'): self
    {
        foreach ($this->listObsoletes($pattern) as $name => $cache) {
            $this->removeCache($name);
        }
        return $this;
    }

    public function deleteAll(string $pattern = '.*'): self
    {
        foreach ($this->listAll($pattern) as $name => $cache) {
            $this->removeCache($name);
        }
        return $this;
    }

    public function listAvailables(string $pattern = '.*'): array
    {
        return \array_filter($this->listAll($pattern), [$this, 'usable']);
    }

    public function listObsoletes(string $pattern = '.*'): array
    {
        return \array_filter($this->listAll($pattern), function ($item) {
            return (true !== $this->usable($item));
        });
    }

    public function listAll(string $pattern = '.*'): array
    {
        $pattern = OwoHelperString::pattern($pattern);
        return \array_filter($this->getCaches(), function ($value, $key) use ($pattern) {
            return (true === OwoHelperString::matches($pattern, \strval($key)));
        }, \ARRAY_FILTER_USE_BOTH);
    }

    public function available(string $name, OwoCacheEntityInterface &$cache = null): bool
    {
        if (true !== \is_null($cache = $this->getCache($this->prepareName($name)))) {
            return (true === $this->usable($cache));
        }
        return false;
    }

    public function usable(OwoCacheEntityInterface $cache): bool
    {
        return (true === $cache->usable($this->duration));
    }

    public static function createCache(string $name, string $content, int $uat = 0): OwoCacheEntityInterface
    {
        return new OwoCacheEntity($name, $content, $uat);
    }

    protected function prepareName(string $name): string
    {
        if (true === $this->encrypt) return \md5($this->sanitizeName($name));
        return $this->sanitizeName($name);
    }

    protected function sanitizeName(string $name): string
    {
        return \preg_replace('/[^a-zA-Z0-9_.-]/', '_', $name);
    }

    abstract protected function getCaches(): array;

    abstract protected function saveCache(string $name, OwoCacheEntityInterface $cache): self;

    abstract protected function getCache(string $name, $default = null): ?OwoCacheEntityInterface;

    abstract protected function removeCache(string $name): self;
}
