<?php

namespace framework\libraries\owo\interfaces\Stackers;

use framework\libraries\owo\interfaces\Stackers\OwoStackerInterface;


interface OwoStackerOnionInterface extends OwoStackerInterface
{
    public function __invoke(object $bundle, $default = null);

    public function process(object $bundle, $default = null);

    public function getStreamer($default = null): callable;
}
