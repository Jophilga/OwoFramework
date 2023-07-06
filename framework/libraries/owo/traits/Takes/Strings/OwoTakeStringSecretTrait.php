<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringSecretTrait
{
    protected $secret = null;

    public function __construct(string $secret)
    {
        $this->setSecret($secret);
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;
        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }
}
