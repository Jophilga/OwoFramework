<?php

namespace framework\libraries\owo\interfaces\Servers;


interface OwoServerStatusInterface
{
    public function setMessageFromCode(): self;

    public function setCode(int $code): self;

    public function getCode(): ?int;

    public function setMessage(string $message): self;

    public function getMessage(): ?string;
}
