<?php

namespace framework\libraries\owo\interfaces\Commons;


interface OwoCommonUtilityInterface
{
    public static function callAsAdditional(string $name, array $arguments);

    public static function __callStatic(string $name, array $arguments);
}
