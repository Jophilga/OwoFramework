<?php

namespace framework\libraries\owo\traits\Takes\Booleans;


trait OwoTakeBooleanCleanTrait
{
    protected $clean = null;

    public function __construct(bool $clean)
    {
        $this->setClean($clean);
    }

    public function setClean(bool $clean): self
    {
        $this->clean = $clean;
        return $this;
    }

    public function getClean(): ?bool
    {
        return $this->clean;
    }
}
