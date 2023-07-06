<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperHeader extends OwoHelper
{
    public const HELPER_HEADER_ADDITIONALS = [];

    public static function getPublishedHeaders(): array
    {
        return \headers_list();
    }

    public static function publishHeaders(array $headers, bool $replace = true)
    {
        foreach ($headers as $name => $value) {
            static::publishHeader($name, $value, $replace);
        }
    }

    public static function publishHeader(string $name, string $value = null, bool $replace = true)
    {
        $header = (true === \is_null($value)) ? $name : \sprintf('%s: %s', $name, $value);
        \header($header, $replace);
    }

    public static function hasSentHeaders(string &$file = null, int &$line = null): bool
    {
        return (true === \headers_sent($file, $line));
    }

    public static function removePublishedHeaders()
    {
        \header_remove();
    }

    public static function removePublishedHeader(string $name)
    {
        \header_remove($name);
    }

    public static function getCurrentRequestHeaders(): array
    {
        return \apache_request_headers() ?: [];
    }

    public static function getCurrentResponseHeaders(): array
    {
        return \apache_response_headers() ?: [];
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_HEADER_ADDITIONALS;
    }

}
