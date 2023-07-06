<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringNameTrait
{
    protected $name = null;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
