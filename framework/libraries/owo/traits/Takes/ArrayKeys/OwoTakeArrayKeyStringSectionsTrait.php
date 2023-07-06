<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyStringSectionsTrait
{
    protected $sections = [];

    public function __construct(array $sections = [])
    {
        $this->setSections($sections);
    }

    public function setSections(array $sections): self
    {
        return $this->emptySections()->addSections($sections);
    }

    public function emptySections(): self
    {
        $this->sections = [];
        return $this;
    }

    public function addSections(array $sections): self
    {
        foreach ($sections as $key => $value) $this->addSection($key, $value);
        return $this;
    }

    public function addSection($key, string $value): self
    {
        $this->sections[$key] = $value;
        return $this;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function hasSection($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->sections, $key));
    }

    public function removeSection($key): self
    {
        unset($this->sections[$key]);
        return $this;
    }

    public function getSection($key, $default = null): ?string
    {
        return $this->sections[$key] ?? $default;
    }
}
