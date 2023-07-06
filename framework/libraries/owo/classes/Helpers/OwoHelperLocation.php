<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperHeader;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperLocation extends OwoHelper
{
    public const HELPER_LOCATION_BACKWARD_JS = 'javascript://history.back()';

    public const HELPER_LOCATION_ADDITIONALS = [];

    public static function moveBackward(bool $permanently = false)
    {
        if (true === $permanently) static::sendMovePermanentlyHeader();
        $location = static::getPreviousLocation(static::HELPER_LOCATION_BACKWARD_JS);
        static::moveTo($location);
    }

    public static function moveTo(string $location, bool $permanently = false)
    {
        if (true === $permanently) static::sendMovePermanentlyHeader();
        OwoHelperHeader::publishHeader('Location', $location);
    }

    public static function getPreviousLocation($default = null): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? $default;
    }

    protected static function sendMovePermanentlyHeader()
    {
        OwoHelperHeader::publishHeader('HTTP/1.1 301 Moved Permanently');
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_LOCATION_ADDITIONALS;
    }
}
