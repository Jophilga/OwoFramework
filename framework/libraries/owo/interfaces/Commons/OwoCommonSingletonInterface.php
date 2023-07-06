<?php

namespace framework\libraries\owo\interfaces\Commons;


interface OwoCommonSingletonInterface
{
    public static function setSingleton(object $instance);

    public static function getSingleton(): self;
}
