<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeConfigsTrait
{
    protected $configs = [];

    public function __construct(array $configs = [])
    {
        $this->setConfigs($configs);
    }

    public function setConfigs(array $configs): self
    {
        return $this->emptyConfigs()->addConfigs($configs);
    }

    public function emptyConfigs(): self
    {
        $this->configs = [];
        return $this;
    }

    public function addConfigs(array $configs): self
    {
        foreach ($configs as $key => $value) $this->addConfig($key, $value);
        return $this;
    }

    public function addConfig($key, $value): self
    {
        $this->configs[$key] = $value;
        return $this;
    }

    public function getConfigs(): array
    {
        return $this->configs;
    }

    public function hasConfig($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->configs, $key));
    }

    public function removeConfig($key): self
    {
        unset($this->configs[$key]);
        return $this;
    }

    public function getConfig($key, $default = null)
    {
        return $this->configs[$key] ?? $default;
    }
}
