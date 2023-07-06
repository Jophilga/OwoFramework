<?php

namespace framework\libraries\owo\traits\Takes\Arrays;


trait OwoTakeArrayMixeStackablesTrait
{
    protected $stackables = [];

    public function __construct(array $stackables = [])
    {
        $this->setStackables($stackables);
    }

    public function setStackables(array $stackables): self
    {
        return $this->emptyStackables()->addStackables($stackables);
    }

    public function emptyStackables(): self
    {
        $this->stackables = [];
        return $this;
    }

    public function addStackables(array $stackables): self
    {
        foreach ($stackables as $stackable) $this->addStackable($stackable);
        return $this;
    }

    public function addStackable($stackable): self
    {
        $this->stackables[] = $stackable;
        return $this;
    }

    public function removeStackable($stackable): self
    {
        $stackables = \array_filter($this->stackables, function ($value) use ($stackable) {
            return ($stackable !== $value);
        });
        $this->stackables = $stackables;
        return $this;
    }

    public function hasStackable($stackable): bool
    {
        return (true === \in_array($stackable, $this->stackables, true));
    }

    public function getStackables(): array
    {
        return $this->stackables;
    }
}
