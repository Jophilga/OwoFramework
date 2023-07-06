<?php

namespace framework\libraries\owo\traits\Takes\Booleans;


trait OwoTakeBooleanAuthTrait
{
    protected $auth = null;

    public function __construct(bool $auth)
    {
        $this->setAuth($auth);
    }

    public function setAuth(bool $auth): self
    {
        $this->auth = $auth;
        return $this;
    }

    public function getAuth(): ?bool
    {
        return $this->auth;
    }
}
