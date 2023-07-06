<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyCallableProceduresTrait
{
    protected $procedures = [];

    public function __construct(array $procedures = [])
    {
        $this->setProcedures($procedures);
    }

    public function setProcedures(array $procedures): self
    {
        return $this->emptyProcedures()->addProcedures($procedures);
    }

    public function emptyProcedures(): self
    {
        $this->procedures = [];
        return $this;
    }

    public function addProcedures(array $procedures): self
    {
        foreach ($procedures as $key => $value) $this->addProcedure($key, $value);
        return $this;
    }

    public function addProcedure($key, callable $value): self
    {
        $this->procedures[$key] = $value;
        return $this;
    }

    public function getProcedures(): array
    {
        return $this->procedures;
    }

    public function hasProcedure($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->procedures, $key));
    }

    public function removeProcedure($key): self
    {
        unset($this->procedures[$key]);
        return $this;
    }

    public function getProcedure($key, $default = null): ?callable
    {
        return $this->procedures[$key] ?? $default;
    }
}
