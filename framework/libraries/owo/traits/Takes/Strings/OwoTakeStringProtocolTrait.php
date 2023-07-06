<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringProtocolTrait
{
    protected $protocol = null;

    public function __construct(string $protocol)
    {
        $this->setProtocol($protocol);
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;
        return $this;
    }

    public function getProtocol(): ?string
    {
        return $this->protocol;
    }
}
