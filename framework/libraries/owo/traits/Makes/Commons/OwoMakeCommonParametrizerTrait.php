<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeParamsTrait;


trait OwoMakeCommonParametrizerTrait
{
    use OwoTakeArrayKeyMixeParamsTrait;

    public function __construct(array $params = [])
    {
        $this->setParams($params);
    }

    public function setParamUser(string $user): self
    {
        return $this->addParam('user', $user);
    }

    public function getParamUser($default = null): ?string
    {
        return $this->getParam('user', $default);
    }

    public function setParamPass(string $pass): self
    {
        return $this->addParam('pass', $pass);
    }

    public function getParamPass($default = null): ?string
    {
        return $this->getParam('pass', $default);
    }

    public function setParamHost(string $host): self
    {
        return $this->addParam('host', $host);
    }

    public function getParamHost($default = null): ?string
    {
        return $this->getParam('host', $default);
    }

    public function setParamPort(int $port): self
    {
        return $this->addParam('port', $port);
    }

    public function getParamPort($default = null): ?int
    {
        return $this->getParam('port', $default);
    }
}
