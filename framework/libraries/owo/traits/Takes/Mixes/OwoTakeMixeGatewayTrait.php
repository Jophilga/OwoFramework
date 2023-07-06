<?php

namespace framework\libraries\owo\traits\Takes\Mixes;


trait OwoTakeMixeGatewayTrait
{
    protected $gateway = null;

    public function __construct($gateway)
    {
        $this->setGateway($gateway);
    }

    public function setGateway($gateway): self
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function getGateway()
    {
        return $this->gateway;
    }
}
