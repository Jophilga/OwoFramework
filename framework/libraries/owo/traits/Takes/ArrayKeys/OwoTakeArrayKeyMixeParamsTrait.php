<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeParamsTrait
{
    protected $params = [];

    public function __construct(array $params = [])
    {
        $this->setParams($params);
    }

    public function setParams(array $params): self
    {
        return $this->emptyParams()->addParams($params);
    }

    public function emptyParams(): self
    {
        $this->params = [];
        return $this;
    }

    public function addParams(array $params): self
    {
        foreach ($params as $key => $value) $this->addParam($key, $value);
        return $this;
    }

    public function addParam($key, $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function hasParam($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->params, $key));
    }

    public function removeParam($key): self
    {
        unset($this->params[$key]);
        return $this;
    }

    public function getParam($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }
}
