<?php

namespace framework\libraries\owo\classes\Seekers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Seekers\OwoSeekerAutoloaderInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use framework\libraries\owo\traits\Takes\Arrays\OwoTakeArrayStringDirsTrait;
use framework\libraries\owo\traits\Takes\Booleans\OwoTakeBooleanThrowTrait;


class OwoSeekerAutoloader implements OwoSeekerAutoloaderInterface
{
    use OwoMakeCommonThrowerTrait;

    use OwoTakeArrayStringDirsTrait;
    use OwoTakeBooleanThrowTrait;

    protected $paths = [];

    public function __construct(array $dirs = [], bool $throw = true)
    {
        $this->setDirs($dirs)->setThrow($throw);
    }

    public function autoloadClass(string $class): self
    {
        $dirs = \array_unique($this->dirs, \SORT_STRING);
        foreach ($dirs as $dir) {
            if (true === \file_exists($path = $this->getClassPath($class, $dir))) {
                return $this->loadPath($path, $class);
            }
        }
        return $this->handleClassNotFound($class);
    }

    public function getClassPath(string $class, string $dir): string
    {
        if (true !== \boolval(\preg_match('/\.php$/', $class))) $class .= ('.php');
        return $dir.'/'.\str_replace('\\', '/', $class);
    }

    public function addIncludePath(string $path): self
    {
        \set_include_path(\get_include_path().\PATH_SEPARATOR.\realpath($path));
        return $this;
    }

    public function register(bool $prepend = true): bool
    {
        return (true === \spl_autoload_register([$this, 'autoloadClass'], $this->throw, $prepend));
    }

    public function unregister(): bool
    {
        return (true === \spl_autoload_unregister([$this, 'autoloadClass']));
    }

    public function getLoadedPaths(): array
    {
        return $this->paths;
    }

    public function getLoadedClassPath(string $class, $default = null): ?string
    {
        return $this->paths[$class] ?? $default;
    }

    protected function handleClassNotFound(string $class): self
    {
        if (true === $this->throw) {
            static::throwRuntimeException(\sprintf('Class [%s] Not Found', $class));
        }
        return $this;
    }

    protected function loadPath(string $path, string $class = null): self
    {
        include_once $path;
        $this->paths[$class ?? $path] = $path;
        return $this;
    }
}
