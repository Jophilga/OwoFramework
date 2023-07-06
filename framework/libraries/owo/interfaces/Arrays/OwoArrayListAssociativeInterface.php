<?php

namespace framework\libraries\owo\interfaces\Arrays;

use framework\libraries\owo\interfaces\Arrays\OwoArrayListInterface;


interface OwoArrayListAssociativeInterface extends OwoArrayListInterface
{
    public function addItemByKey($key, $item): self;

    public function hasItemByKey($key): bool;

    public function removeItemByKey($key): self;

    public function getItemByKey($key, $default = null);
}
