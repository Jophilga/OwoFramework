<?php

namespace framework\libraries\owo\traits\Takes\Integers;


trait OwoTakeIntegerPriorTrait
{
    protected $prior = null;

    public function __construct(int $prior)
    {
        $this->setPrior($prior);
    }

    public function setPrior(int $prior): self
    {
        $this->prior = $prior;
        return $this;
    }

    public function getPrior(): ?int
    {
        return $this->prior;
    }
}
