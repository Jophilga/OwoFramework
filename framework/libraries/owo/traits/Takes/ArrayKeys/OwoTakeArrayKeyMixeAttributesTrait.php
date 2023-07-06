<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeAttributesTrait
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function setAttributes(array $attributes): self
    {
        return $this->emptyAttributes()->addAttributes($attributes);
    }

    public function emptyAttributes(): self
    {
        $this->attributes = [];
        return $this;
    }

    public function addAttributes(array $attributes): self
    {
        foreach ($attributes as $key => $value) $this->addAttribute($key, $value);
        return $this;
    }

    public function addAttribute($key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function hasAttribute($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->attributes, $key));
    }

    public function removeAttribute($key): self
    {
        unset($this->attributes[$key]);
        return $this;
    }

    public function getAttribute($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }
}
