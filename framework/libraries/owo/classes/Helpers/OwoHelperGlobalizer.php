<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperGlobalizer extends OwoHelper
{
    public const HELPER_GLOBALIZER_ADDITIONALS = [];

    public static function getGlobalsFromGlobals(): array
    {
        return $GLOBALS ?? [];
    }

    public static function removeGlobals(array $keys)
    {
        foreach ($keys as $key) static::removeGlobal($key);
    }

    public static function removeGlobal($key)
    {
        unset($GLOBALS[$key]);
    }

    public static function addGlobals(array $globals)
    {
        foreach ($globals as $key => $value) static::addGlobal($key, $value);
    }

    public static function addGlobal($key, $value)
    {
        $GLOBALS[$key] = $value;
    }

    public static function hasGlobal($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($GLOBALS, $key));
    }

    public static function getGlobal($key, $default = null)
    {
        return $GLOBALS[$key] ?? $default;
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_GLOBALIZER_ADDITIONALS;
    }
}
