<?php

namespace framework\libraries\owo\traits\Takes\Arrays;


trait OwoTakeArrayStringDirsTrait
{
    protected $dirs = [];

    public function __construct(array $dirs = [])
    {
        $this->setDirs($dirs);
    }

    public function setDirs(array $dirs): self
    {
        return $this->emptyDirs()->addDirs($dirs);
    }

    public function emptyDirs(): self
    {
        $this->dirs = [];
        return $this;
    }

    public function addDirs(array $dirs): self
    {
        foreach ($dirs as $dir) $this->addDir($dir);
        return $this;
    }

    public function addDir(string $dir): self
    {
        $this->dirs[] = $dir;
        return $this;
    }

    public function removeDir(string $dir): self
    {
        $dirs = \array_filter($this->dirs, function ($item) use ($dir) {
            return ($dir !== $item);
        });
        $this->dirs = $dirs;
        return $this;
    }

    public function hasDir(string $dir): bool
    {
        return (true === \in_array($dir, $this->dirs, true));
    }

    public function getDirs(): array
    {
        return $this->dirs;
    }
}
