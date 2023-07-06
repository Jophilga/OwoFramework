<?php

namespace framework\libraries\owo\traits\Takes\Integers;


trait OwoTakeIntegerCodeTrait
{
    protected $code = null;

    public function __construct(int $code)
    {
        $this->setCode($code);
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }
}
