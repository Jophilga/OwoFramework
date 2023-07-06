<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\classes\Helpers\OwoHelperString;


class OwoHelperCapturer extends OwoHelper
{
    public const HELPER_CAPTURER_ADDITIONALS = [];

    public static function captureWholeHttpServerRequestUrl(): string
    {
        $port = static::captureServerPort(80);
        $scheme = ('http');
        if ((true === static::captureServerHasHttpsOn()) || (443 === $port)) {
            $scheme = ('https');
        }

        $host = static::captureServerHost('localhost');
        $url = static::captureServerRequestUrl('/');
        return \sprintf('%s://%s:%d%s', $scheme, $host, $port, $url);
    }

    public static function captureServerHasHttpsOn(): bool
    {
        return ('on' === OwoHelperString::lowerCase($_SERVER['HTTPS'] ?? 'off'));
    }

    public static function captureServerClientHost($default = null): string
    {
        $matches = \explode(':', \strval($_SERVER['HTTP_CLIENT_IP'] ??
            $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED'] ??
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_FORWARDED'] ??
            $_SERVER['HTTP_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ??
            $default)
        );
        return $matches[0];
    }

    public static function captureServerClientLang($default = 'en'): string
    {
        $lang = OwoHelperString::lowerCase($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? $default);
        return OwoHelperString::subString($lang, 0, 2);
    }

    public static function captureServerClientPort($default = null): int
    {
        return \intval($_SERVER['REMOTE_PORT'] ?? $default);
    }

    public static function captureServerRequestMethod($default = 'GET'): string
    {
        return \strval($_SERVER['REQUEST_METHOD'] ?? $default);
    }

    public static function captureServerRequestUrl($default = '/'): string
    {
        return \strval($_SERVER['REQUEST_URI'] ?? $default);
    }

    public static function captureServerHost($default = 'localhost'): string
    {
        $matches = \explode(':', \strval($_SERVER['HTTP_HOST'] ?? $default));
        return $matches[0];
    }

    public static function captureServerPort($default = 80): int
    {
        return \intval($_SERVER['SERVER_PORT'] ?? $default);
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_CAPTURER_ADDITIONALS;
    }
}
