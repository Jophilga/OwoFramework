<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringPublicKeyTrait
{
    protected $public_key = null;

    public function __construct(string $public_key)
    {
        $this->setPublicKey($public_key);
    }

    public function setPublicKey(string $public_key): self
    {
        $this->public_key = $public_key;
        return $this;
    }

    public function getPublicKey(): ?string
    {
        return $this->public_key;
    }
}
