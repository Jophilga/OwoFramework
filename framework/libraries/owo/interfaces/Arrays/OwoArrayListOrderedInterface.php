<?php

namespace framework\libraries\owo\interfaces\Arrays;

use framework\libraries\owo\interfaces\Arrays\OwoArrayListInterface;


interface OwoArrayListOrderedInterface extends OwoArrayListInterface
{
    public function firstItem($default = null);

    public function lastItem($default = null);

    public function addItemByIndex(int $index, $item): self;

    public function getItemByIndex(int $index, $default = null);

    public function hasItemByIndex(int $index): bool;

    public function removeItemByIndex(int $index): self;

    public function stackItem($item): self;

    public function unstackItem();

    public function queueItem($item): self;

    public function unqueueItem();
}
