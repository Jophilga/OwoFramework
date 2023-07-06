<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyCallableMeasuresTrait
{
    protected $measures = [];

    public function __construct(array $measures = [])
    {
        $this->setMeasures($measures);
    }

    public function setMeasures(array $measures): self
    {
        return $this->emptyMeasures()->addMeasures($measures);
    }

    public function emptyMeasures(): self
    {
        $this->measures = [];
        return $this;
    }

    public function addMeasures(array $measures): self
    {
        foreach ($measures as $key => $value) $this->addMeasure($key, $value);
        return $this;
    }

    public function addMeasure($key, callable $value): self
    {
        $this->measures[$key] = $value;
        return $this;
    }

    public function getMeasures(): array
    {
        return $this->measures;
    }

    public function hasMeasure($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->measures, $key));
    }

    public function removeMeasure($key): self
    {
        unset($this->measures[$key]);
        return $this;
    }

    public function getMeasure($key, $default = null): ?callable
    {
        return $this->measures[$key] ?? $default;
    }
}
