<?php

namespace framework\libraries\owo\classes\Arrays;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Arrays\OwoArrayListAssociativeInterface;


class OwoArrayListAssociative extends OwoArrayList implements OwoArrayListAssociativeInterface
{
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    public function addItems(array $items): self
    {
        foreach ($items as $key => $item) {
            $this->addItemByKey($key, $item);
        }
        return $this;
    }

    public function addItemByKey($key, $item): self
    {
        $this->items[$key] = $item;
        return $this;
    }

    public function hasItemByKey($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->items, $key));
    }

    public function removeItemByKey($key): self
    {
        unset($this->items[$key]);
        return $this;
    }

    public function getItemByKey($key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }
}
