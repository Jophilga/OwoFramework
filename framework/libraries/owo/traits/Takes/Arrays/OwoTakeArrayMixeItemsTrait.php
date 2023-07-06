<?php

namespace framework\libraries\owo\traits\Takes\Arrays;


trait OwoTakeArrayMixeItemsTrait
{
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->setItems($items);
    }

    public function setItems(array $items): self
    {
        return $this->emptyItems()->addItems($items);
    }

    public function emptyItems(): self
    {
        $this->items = [];
        return $this;
    }

    public function addItems(array $items): self
    {
        foreach ($items as $item) $this->addItem($item);
        return $this;
    }

    public function addItem($item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function removeItem($item): self
    {
        $items = \array_filter($this->items, function ($value) use ($item) {
            return ($item !== $value);
        });
        $this->items = $items;
        return $this;
    }

    public function hasItem($item): bool
    {
        return (true === \in_array($item, $this->items, true));
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
