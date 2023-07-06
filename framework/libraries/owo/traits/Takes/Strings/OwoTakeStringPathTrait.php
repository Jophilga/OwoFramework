<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringPathTrait
{
    protected $path = null;

    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
