<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringPrefixTrait
{
    protected $prefix = null;

    public function __construct(string $prefix)
    {
        $this->setPrefix($prefix);
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }
}
