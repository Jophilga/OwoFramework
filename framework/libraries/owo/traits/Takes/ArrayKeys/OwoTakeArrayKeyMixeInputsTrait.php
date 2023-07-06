<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeInputsTrait
{
    protected $inputs = [];

    public function __construct(array $inputs = [])
    {
        $this->setInputs($inputs);
    }

    public function setInputs(array $inputs): self
    {
        return $this->emptyInputs()->addInputs($inputs);
    }

    public function emptyInputs(): self
    {
        $this->inputs = [];
        return $this;
    }

    public function addInputs(array $inputs): self
    {
        foreach ($inputs as $key => $value) $this->addInput($key, $value);
        return $this;
    }

    public function addInput($key, $value): self
    {
        $this->inputs[$key] = $value;
        return $this;
    }

    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function hasInput($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->inputs, $key));
    }

    public function removeInput($key): self
    {
        unset($this->inputs[$key]);
        return $this;
    }

    public function getInput($key, $default = null)
    {
        return $this->inputs[$key] ?? $default;
    }
}
