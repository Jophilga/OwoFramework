<?php

namespace framework\libraries\owo\traits\Takes\Integers;


trait OwoTakeIntegerModeTrait
{
    protected $mode = null;

    public function __construct(int $mode)
    {
        $this->setMode($mode);
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }
}
