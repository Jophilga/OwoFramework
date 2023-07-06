<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringMessageTrait
{
    protected $message = null;

    public function __construct(string $message)
    {
        $this->setMessage($message);
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
