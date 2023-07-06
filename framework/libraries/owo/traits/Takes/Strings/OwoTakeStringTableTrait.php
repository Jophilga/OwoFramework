<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringTableTrait
{
    protected $table = null;

    public function __construct(string $table)
    {
        $this->setTable($table);
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function getTable(): ?string
    {
        return $this->table;
    }
}
