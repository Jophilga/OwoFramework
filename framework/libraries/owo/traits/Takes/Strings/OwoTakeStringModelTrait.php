<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringModelTrait
{
    protected $model = null;

    public function __construct(string $model)
    {
        $this->setModel($model);
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }
}
