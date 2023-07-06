<?php

namespace framework\libraries\owo\interfaces\Cores;


interface OwoCoreViewInterface
{
    public function setAttributes(array $attributes): self;

    public function emptyAttributes(): self;

    public function addAttributes(array $attributes): self;

    public function addAttribute($key, $value): self;

    public function getAttributes(): array;

    public function hasAttribute($key): bool;

    public function removeAttribute($key): self;

    public function getAttribute($key, $default = null);
}
