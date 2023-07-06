<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringDirTrait
{
    protected $dir = null;

    public function __construct(string $dir)
    {
        $this->setDir($dir);
    }

    public function setDir(string $dir): self
    {
        $this->dir = $dir;
        return $this;
    }

    public function getDir(): ?string
    {
        return $this->dir;
    }
}
