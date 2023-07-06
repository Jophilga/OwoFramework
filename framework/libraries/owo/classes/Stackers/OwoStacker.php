<?php

namespace framework\libraries\owo\classes\Stackers;

use framework\libraries\owo\interfaces\Stackers\OwoStackerInterface;

use framework\libraries\owo\traits\Takes\Arrays\OwoTakeArrayMixeStackablesTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


abstract class OwoStacker implements OwoStackerInterface
{
    use OwoTakeArrayMixeStackablesTrait;

    use OwoMakeCommonThrowerTrait;

    public function __construct(array $stackables = [])
    {
        $this->setStackables($stackables);
    }

    public function getStackableIndexes($stackable): array
    {
        $indexes = [];
        foreach ($this->stackables as $index => $value) {
            if ($stackable === $value) $indexes[] = $index;
        }
        return $indexes;
    }

    public function getStackableByIndex(int $index, $default = null)
    {
        return $this->stackables[$index] ?? $default;
    }

    public function stack($stackable): self
    {
        \array_unshift($this->stackables, $stackable);
        return $this;
    }

    public function unstack()
    {
        return \array_shift($this->stackables);
    }

    public function reverseUnstack()
    {
        return \array_pop($this->stackables);
    }

    abstract public function addStackable($stackable): self;
}
