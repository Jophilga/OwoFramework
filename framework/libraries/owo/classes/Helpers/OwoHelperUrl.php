<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperUrl extends OwoHelper
{
    public const HELPER_URL_ADDITIONALS = [];

    public static function components(string $url): array
    {
        return \parse_url($url) ?: [];
    }

    public static function scheme(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_SCHEME) ?? $default;
    }

    public static function user(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_USER) ?? $default;
    }

    public static function pass(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_PASS) ?? $default;
    }

    public static function host(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_HOST) ?? $default;
    }

    public static function port(string $url, $default = null): ?int
    {
        return \parse_url($url, \PHP_URL_PORT) ?? $default;
    }

    public static function path(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_PATH) ?? $default;
    }

    public static function query(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_QUERY) ?? $default;
    }

    public static function fragment(string $url, $default = null): ?string
    {
        return \parse_url($url, \PHP_URL_FRAGMENT) ?? $default;
    }

    public static function param(string $url, string $name, $default = null): ?string
    {
        $params = static::params($url);
        return $params[$name] ?? $default;
    }

    public static function params(string $url): array
    {
        $params = [];
        if (true !== empty($query = static::query($url))) {
            OwoHelperString::parseToArray($query);
        }
        return $params;
    }

    public static function createFromComponents(array $components): string
    {
        $matches = [];
        if (true !== empty($components['scheme'])) {
            $matches[] = \sprintf('%s://', $components['scheme']);
        }

        if (true !== empty($components['user'])) {
            $userpass = $components['user'];
            if (true !== empty($components['pass'])) {
                $userpass .= \sprintf(':%s', $components['pass']);
            }
            $matches[] = \sprintf('%s@', $userpass);
        }

        if (true !== empty($components['host'])) {
            $host = $components['host'];
            if (true !== empty($components['port'])) {
                $host .= \sprintf(':%d', $components['port']);
            }
            $matches[] = $host;
        }

        if (true !== empty($components['path'])) {
            $matches[] = \sprintf('/%s', ltrim($components['path'], '/'));
        }

        if (true !== empty($components['query'])) {
            $matches[] = \sprintf('?%s', $components['query']);
        }

        if (true !== empty($components['fragment'])) {
            $matches[] = \sprintf('#%s', $components['fragment']);
        }
        return \implode('', $matches);
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_URL_ADDITIONALS;
    }
}
