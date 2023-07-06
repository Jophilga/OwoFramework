<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringTokenTrait
{
    protected $token = null;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
