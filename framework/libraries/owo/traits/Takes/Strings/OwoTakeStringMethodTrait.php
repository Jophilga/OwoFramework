<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringMethodTrait
{
    protected $method = null;

    public function __construct(string $method)
    {
        $this->setMethod($method);
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }
}
