<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringPatternTrait
{
    protected $pattern = null;

    public function __construct(string $pattern)
    {
        $this->setPattern($pattern);
    }

    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }
}
