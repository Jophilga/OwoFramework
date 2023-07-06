<?php

namespace framework\libraries\owo\traits\Takes\Integers;


trait OwoTakeIntegerUatTrait
{
    protected $uat = null;

    public function __construct(int $uat)
    {
        $this->setUat($uat);
    }

    public function setUat(int $uat): self
    {
        $this->uat = $uat;
        return $this;
    }

    public function getUat(): ?int
    {
        return $this->uat;
    }
}
