<?php

namespace framework\libraries\owo\traits\Takes\Integers;


trait OwoTakeIntegerPortTrait
{
    protected $port = null;

    public function __construct(int $port)
    {
        $this->setPort($port);
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }
}
