<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringModeTrait
{
    protected $mode = null;

    public function __construct(string $mode)
    {
        $this->setMode($mode);
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }
}
