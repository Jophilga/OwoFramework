<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperResource extends OwoHelper
{
    public const HELPER_RESOURCE_ADDITIONALS = [];

    public static function getStreamRow(\resource $stream, int $length, string $ending = ''): ?string
    {
        if (false !== ($line = \stream_get_line($stream, $length, $ending))) return $line;
        return null;
    }

    public static function getStreamContents(\resource $stream): ?string
    {
        if (false !== ($outcome = \stream_get_contents($stream))) return $outcome;
        return null;
    }

    public static function createStream(string $url, string $mode, \resource $context = null): ?\resource
    {
        if (false !== ($stream = \fopen($url, $mode, false, $context))) return $stream;
        return null;
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_RESOURCE_ADDITIONALS;
    }

}
