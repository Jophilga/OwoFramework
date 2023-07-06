<?php

namespace framework\libraries\owo\traits\Takes\Callables;


trait OwoTakeCallableResolerTrait
{
    protected $resolver = null;

    public function __construct(callable $resolver)
    {
        $this->setResolver($resolver);
    }

    public function setResolver(callable $resolver): self
    {
        $this->resolver = $resolver;
        return $this;
    }

    public function getResolver(): ?callable
    {
        return $this->resolver;
    }
}
