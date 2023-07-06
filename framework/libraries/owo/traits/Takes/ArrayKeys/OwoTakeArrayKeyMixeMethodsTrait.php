<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeMethodsTrait
{
    protected $methods = [];

    public function __construct(array $methods = [])
    {
        $this->setMethods($methods);
    }

    public function setMethods(array $methods): self
    {
        return $this->emptyMethods()->addMethods($methods);
    }

    public function emptyMethods(): self
    {
        $this->methods = [];
        return $this;
    }

    public function addMethods(array $methods): self
    {
        foreach ($methods as $key => $value) $this->addMethod($key, $value);
        return $this;
    }

    public function addMethod($key, $value): self
    {
        $this->methods[$key] = $value;
        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function hasMethod($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->methods, $key));
    }

    public function removeMethod($key): self
    {
        unset($this->methods[$key]);
        return $this;
    }

    public function getMethod($key, $default = null)
    {
        return $this->methods[$key] ?? $default;
    }
}
