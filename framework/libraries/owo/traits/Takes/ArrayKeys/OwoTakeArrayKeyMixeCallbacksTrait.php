<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeCallbacksTrait
{
    protected $callbacks = [];

    public function __construct(array $callbacks = [])
    {
        $this->setCallbacks($callbacks);
    }

    public function setCallbacks(array $callbacks): self
    {
        return $this->emptyCallbacks()->addCallbacks($callbacks);
    }

    public function emptyCallbacks(): self
    {
        $this->callbacks = [];
        return $this;
    }

    public function addCallbacks(array $callbacks): self
    {
        foreach ($callbacks as $key => $value) $this->addCallback($key, $value);
        return $this;
    }

    public function addCallback($key, $value): self
    {
        $this->callbacks[$key] = $value;
        return $this;
    }

    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    public function hasCallback($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->callbacks, $key));
    }

    public function removeCallback($key): self
    {
        unset($this->callbacks[$key]);
        return $this;
    }

    public function getCallback($key, $default = null)
    {
        return $this->callbacks[$key] ?? $default;
    }
}
