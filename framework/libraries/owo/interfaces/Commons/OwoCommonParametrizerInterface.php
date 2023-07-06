<?php

namespace framework\libraries\owo\interfaces\Commons;


interface OwoCommonParametrizerInterface
{
    public function setParamUser(string $user): self;

    public function getParamUser($default = null): ?string;

    public function setParamPass(string $pass): self;

    public function getParamPass($default = null): ?string;

    public function setParamHost(string $host): self;

    public function getParamHost($default = null): ?string;

    public function setParamPort(int $port): self;

    public function getParamPort($default = null): ?int;

    public function setParams(array $params): self;

    public function emptyParams(): self;

    public function addParams(array $params): self;

    public function addParam($key, $value): self;

    public function getParams(): array;

    public function hasParam($key): bool;

    public function removeParam($key): self;

    public function getParam($key, $default = null);
}
