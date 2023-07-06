<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringHostTrait
{
    protected $host = null;

    public function __construct(string $host)
    {
        $this->setHost($host);
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }
}
