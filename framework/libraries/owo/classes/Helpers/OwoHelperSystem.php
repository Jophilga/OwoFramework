<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperSystem extends OwoHelper
{
    public const HELPER_MIX_ADDITIONALS = [];

    public static function isLoadedExtension(string $extension): bool
    {
        $extension = OwoHelperString::lowerCase($extension);
        if (true === \extension_loaded($extension)) {
            return (true === \dl(\sprintf('%s.io', $extension)));
        }
        return false;
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_MIX_ADDITIONALS;
    }
}
