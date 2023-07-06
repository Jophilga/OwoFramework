<?php

namespace framework\libraries\owo\classes\Arrays;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Arrays\OwoArrayListOrderedInterface;


class OwoArrayListOrdered extends OwoArrayList implements OwoArrayListOrderedInterface
{
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    public function firstItem($default = null)
    {
        return $this->getItemByIndex(0, $default);
    }

    public function lastItem($default = null)
    {
        if (0 < ($count = $this->countItems())) {
            return $this->getItemByIndex($count - 1, $default);
        }
        return $default;
    }

    public function addItemByIndex(int $index, $item): self
    {
        if (true === $this->hasItemByIndex($index)) {
            $this->items[$index] = $item;
        }
        return $this;
    }

    public function getItemByIndex(int $index, $default = null)
    {
        return $this->items[$index] ?? $default;
    }

    public function hasItemByIndex(int $index): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->items, $index));
    }

    public function removeItemByIndex(int $index): self
    {
        unset($this->items[$index]);
        return $this->reorderItemsIndexes();
    }

    public function stackItem($item): self
    {
        \array_unshift($this->items, $item);
        return $this;
    }

    public function unstackItem()
    {
        return \array_shift($this->items);
    }

    public function queueItem($item): self
    {
        \array_push($this->items, $item);
        return $this;
    }

    public function unqueueItem()
    {
        return \array_pop($this->items);
    }

    protected function reorderItemsIndexes(): self
    {
        $this->items = \array_values($this->items);
        return $this;
    }
}
