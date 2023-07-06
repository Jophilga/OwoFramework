<?php

namespace framework\libraries\owo\interfaces\Ciphers;

use framework\libraries\owo\interfaces\Ciphers\OwoCipherInterface;


interface OwoCipherOpenSslInterface extends OwoCipherInterface
{
    public function setSecret(string $secret): self;

    public function getSecret(): ?string;

    public function setParams(array $params): self;

    public function emptyParams(): self;

    public function addParams(array $params): self;

    public function addParam($key, $value): self;

    public function getParams(): array;

    public function hasParam($key): bool;

    public function removeParam($key): self;

    public function getParam($key, $default = null);
}
