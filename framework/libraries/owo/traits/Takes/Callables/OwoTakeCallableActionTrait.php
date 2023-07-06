<?php

namespace framework\libraries\owo\traits\Takes\Callables;


trait OwoTakeCallableActionTrait
{
    protected $action = null;

    public function __construct(callable $action)
    {
        $this->setAction($action);
    }

    public function setAction(callable $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getAction(): ?callable
    {
        return $this->action;
    }
}
