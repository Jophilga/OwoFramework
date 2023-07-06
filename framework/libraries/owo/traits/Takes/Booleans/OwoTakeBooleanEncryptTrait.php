<?php

namespace framework\libraries\owo\traits\Takes\Booleans;


trait OwoTakeBooleanEncryptTrait
{
    protected $encrypt = null;

    public function __construct(bool $encrypt)
    {
        $this->setEncrypt($encrypt);
    }

    public function setEncrypt(bool $encrypt): self
    {
        $this->encrypt = $encrypt;
        return $this;
    }

    public function getEncrypt(): ?bool
    {
        return $this->encrypt;
    }
}
