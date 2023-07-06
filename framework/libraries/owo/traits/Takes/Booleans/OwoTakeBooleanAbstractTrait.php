<?php

namespace framework\libraries\owo\traits\Takes\Booleans;


trait OwoTakeBooleanAbstractTrait
{
    protected $abstract = null;

    public function __construct(bool $abstract)
    {
        $this->setAbstract($abstract);
    }

    public function setAbstract(bool $abstract): self
    {
        $this->abstract = $abstract;
        return $this;
    }

    public function getAbstract(): ?bool
    {
        return $this->abstract;
    }
}
