<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyObjectInstancesTrait
{
    protected $instances = [];

    public function __construct(array $instances = [])
    {
        $this->setInstances($instances);
    }

    public function setInstances(array $instances): self
    {
        return $this->emptyInstances()->addInstances($instances);
    }

    public function emptyInstances(): self
    {
        $this->instances = [];
        return $this;
    }

    public function addInstances(array $instances): self
    {
        foreach ($instances as $key => $value) $this->addInstance($key, $value);
        return $this;
    }

    public function addInstance($key, object $value): self
    {
        $this->instances[$key] = $value;
        return $this;
    }

    public function getInstances(): array
    {
        return $this->instances;
    }

    public function hasInstance($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->instances, $key));
    }

    public function removeInstance($key): self
    {
        unset($this->instances[$key]);
        return $this;
    }

    public function getInstance($key, $default = null): ?object
    {
        return $this->instances[$key] ?? $default;
    }
}
