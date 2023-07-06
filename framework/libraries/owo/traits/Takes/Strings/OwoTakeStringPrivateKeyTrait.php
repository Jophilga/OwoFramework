<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringPrivateKeyTrait
{
    protected $private_key = null;

    public function __construct(string $private_key)
    {
        $this->setPrivateKey($private_key);
    }

    public function setPrivateKey(string $private_key): self
    {
        $this->private_key = $private_key;
        return $this;
    }

    public function getPrivateKey(): ?string
    {
        return $this->private_key;
    }
}
