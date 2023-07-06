<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Varies\OwoVaryDisplayerInterface;


interface OwoCommonDisplayableInterface
{
    public function display(OwoVaryDisplayerInterface $displayer): self;
}
