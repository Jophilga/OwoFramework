<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeOptionsTrait
{
    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    public function setOptions(array $options): self
    {
        return $this->emptyOptions()->addOptions($options);
    }

    public function emptyOptions(): self
    {
        $this->options = [];
        return $this;
    }

    public function addOptions(array $options): self
    {
        foreach ($options as $key => $value) $this->addOption($key, $value);
        return $this;
    }

    public function addOption($key, $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOption($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->options, $key));
    }

    public function removeOption($key): self
    {
        unset($this->options[$key]);
        return $this;
    }

    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }
}
