<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperCookier extends OwoHelper
{
    public const HELPER_COOKIER_DEFAULT_PARAMS = [
        'httponly' => true, 'secure' => true,
    ];

    public const HELPER_COOKIER_DEFAULT_LIFETIME = 86400;

    public const HELPER_COOKIER_ADDITIONALS = [];

    public static function getCookiesFromGlobals(): array
    {
        return $_COOKIE ?? [];
    }

    public static function removeCookies(array $names)
    {
        foreach ($names as $name) static::removeCookie($name, $params);
    }

    public static function removeCookie(string $name): bool
    {
        $params = ['expires' => \time() - static::COOKIER_DEFAULT_LIFETIME];
        return (true === static::addCookie($name, '', $params));
    }

    public static function addCookies(array $cookies, array $params = [])
    {
        foreach ($cookies as $key => $value) {
            static::addCookie($key, $value, $params);
        }
    }

    public static function addCookie(string $name, string $value, array $params = []): bool
    {
        $params = \array_merge(static::getCurrentCookieParams(), $params);
        if (true !== OwoHelperArray::hasSetKey($params, 'expires')) {
            $params['expires'] = \time() + static::HELPER_COOKIER_DEFAULT_LIFETIME;
        }
        return (true === \setcookie($name, $value, $params));
    }

    public static function hasCookie(string $name): bool
    {
        return (true === OwoHelperArray::hasSetKey($_COOKIE, $name));
    }

    public static function getCookie(string $name, $default = null): ?string
    {
        return $_COOKIE[$name] ?? $default;
    }

    public static function getSessionCookieParams(): array
    {
        return \session_get_cookie_params();
    }

    public static function getCurrentCookieParams(): array
    {
        $params = static::HELPER_COOKIER_DEFAULT_PARAMS;
        $params = \array_merge(static::getSessionCookieParams(), $params);
        return $params;
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_COOKIER_ADDITIONALS;
    }
}
