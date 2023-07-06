<?php

namespace framework\libraries\owo\traits\Takes\Booleans;


trait OwoTakeBooleanThrowTrait
{
    protected $throw = null;

    public function __construct(bool $throw)
    {
        $this->setThrow($throw);
    }

    public function setThrow(bool $throw): self
    {
        $this->throw = $throw;
        return $this;
    }

    public function getThrow(): ?bool
    {
        return $this->throw;
    }
}
