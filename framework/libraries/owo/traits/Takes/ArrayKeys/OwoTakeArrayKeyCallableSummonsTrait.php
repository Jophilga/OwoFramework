<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyCallableSummonsTrait
{
    protected $summons = [];

    public function __construct(array $summons = [])
    {
        $this->setSummons($summons);
    }

    public function setSummons(array $summons): self
    {
        return $this->emptySummons()->addSummons($summons);
    }

    public function emptySummons(): self
    {
        $this->summons = [];
        return $this;
    }

    public function addSummons(array $summons): self
    {
        foreach ($summons as $key => $value) $this->addSummon($key, $value);
        return $this;
    }

    public function addSummon($key, callable $value): self
    {
        $this->summons[$key] = $value;
        return $this;
    }

    public function getSummons(): array
    {
        return $this->summons;
    }

    public function hasSummon($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->summons, $key));
    }

    public function removeSummon($key): self
    {
        unset($this->summons[$key]);
        return $this;
    }

    public function getSummon($key, $default = null): ?callable
    {
        return $this->summons[$key] ?? $default;
    }
}
