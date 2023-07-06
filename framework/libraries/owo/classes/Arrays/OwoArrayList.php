<?php

namespace framework\libraries\owo\classes\Arrays;

use framework\libraries\owo\interfaces\Arrays\OwoArrayListInterface;

use framework\libraries\owo\traits\Takes\Arrays\OwoTakeArrayMixeItemsTrait;


class OwoArrayList implements OwoArrayListInterface
{
    use OwoTakeArrayMixeItemsTrait;

    public function __construct(array $items = [])
    {
        $this->setItems($items);
    }

    public function countItems(): int
    {
        return \count($this->items);
    }

    public function sortItems(callable $callback): bool
    {
        return (true === \usort($this->items, $callback));
    }

    public function isEmpty(): bool
    {
        return (true === empty($this->items));
    }
}
