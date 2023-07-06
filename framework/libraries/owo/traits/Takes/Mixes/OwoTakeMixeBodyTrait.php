<?php

namespace framework\libraries\owo\traits\Takes\Mixes;


trait OwoTakeMixeBodyTrait
{
    protected $body = null;

    public function __construct($body)
    {
        $this->setBody($body);
    }

    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }
}
