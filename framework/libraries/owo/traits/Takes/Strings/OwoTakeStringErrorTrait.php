<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringErrorTrait
{
    protected $error = null;

    public function __construct(string $error)
    {
        $this->setError($error);
    }

    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}
