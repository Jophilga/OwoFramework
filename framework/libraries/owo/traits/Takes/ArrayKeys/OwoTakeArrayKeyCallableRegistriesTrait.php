<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyCallableRegistriesTrait
{
    protected $registries = [];

    public function __construct(array $registries = [])
    {
        $this->setRegistries($registries);
    }

    public function setRegistries(array $registries): self
    {
        return $this->emptyRegistries()->addRegistries($registries);
    }

    public function emptyRegistries(): self
    {
        $this->registries = [];
        return $this;
    }

    public function addRegistries(array $registries): self
    {
        foreach ($registries as $key => $value) $this->addRegistry($key, $value);
        return $this;
    }

    public function addRegistry($key, callable $value): self
    {
        $this->registries[$key] = $value;
        return $this;
    }

    public function getRegistries(): array
    {
        return $this->registries;
    }

    public function hasRegistry($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->registries, $key));
    }

    public function removeRegistry($key): self
    {
        unset($this->registries[$key]);
        return $this;
    }

    public function getRegistry($key, $default = null): ?callable
    {
        return $this->registries[$key] ?? $default;
    }
}
