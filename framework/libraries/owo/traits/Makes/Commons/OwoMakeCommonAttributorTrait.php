<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeAttributesTrait;


trait OwoMakeCommonAttributorTrait
{
    use OwoTakeArrayKeyMixeAttributesTrait;

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function addAttribute($key, $value): self
    {
        if (true !== $this->hasAttribute($key)) $this->replaceAttribute($key, $value);
        return $this;
    }

    public function replaceAttribute($key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }
}
