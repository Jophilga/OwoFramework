<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperEncoder;
use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoHelperString extends OwoHelper
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_STRING_ADDITIONALS = [];

    public const HELPER_STRING_ENCODING = 'UTF-8';

    public const HELPER_STRING_SPECIALCHAR_PATTERN = '/([^a-zA-Z0-9_-])/';

    public const HELPER_STRING_SLUG_PATTERN = '/^(?<slug>[\w-_]*)$/';

    public const HELPER_STRING_WRAPPERS = [
        "\t", "\n", "\r", "\0", "\n\r", "\r\n", "\x0B",
    ];

    public const HELPER_STRING_ALPHABET = [
        'vowels' => [ 'a', 'e', 'i', 'o', 'u', ],
        'consonants' => [
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n',
            'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z',
        ],
    ];

    public static function pattern(string $str): string
    {
        if (true === static::matches('/^\/.*\/$/', $str)) return $str;
        return \sprintf('/%s/', $str);
    }

    public static function matchesSlug(string $str, array &$matches = null): bool
    {
        return (true === static::matches(static::HELPER_STRING_SLUG_PATTERN, $str, $matches));
    }

    public static function matches(string $pattern, string $str, array &$matches = null): bool
    {
        return (true === \boolval(\preg_match($pattern, $str, $matches)));
    }

    public static function hasLengthBetween(string $str, int $min, int $max): bool
    {
        if ($min <= ($length = static::length($str))) return ($length <= $max);
        return false;
    }

    public static function length(string $str): int
    {
        if (true === \function_exists('mb_strlen')) return \mb_strlen($str, '8bit');
        return \strlen($str);
    }

    public static function parseToArray(string $str): array
    {
        $data = [];
        \parse_str($str, $data);
        return $data;
    }

    public static function parseToDateTime(string $str, string $format = 'Y-m-d'): ?\DateTime
    {
        return \DateTime::createFromFormat($format, $str) ?: null;
    }

    public static function randomPassPhrase(int $length = 4, int $amount = 3, string $separator = '-'): string
    {
        $sections = [];
        for ($i = 0; $i < $amount; $i++) {
            $sections[] = static::random($length);
        }
        return \implode($separator, $sections);
    }

    public static function random(int $length = 20): string
    {
        return static::subString(\bin2hex(\random_bytes($length)), 0, $length);
    }

    public static function randomPhrase(int $amount = 4, int $min = 2, int $max = 10): string
    {
        $terms = [];
        for ($i = 0; $i < $amount; $i++) {
            $terms[] = static::randomTerm(\random_int($min, $max));
        }
        return \implode(' ', $terms);
    }

    public static function randomTerm(int $length = 4): string
    {
        $letters = [];
        for ($i = 0; $i < $length; $i++) {
            $letters[] = static::randomizeStrings(static::HELPER_STRING_ALPHABET['consonants']);
            $letters[] = static::randomizeStrings(static::HELPER_STRING_ALPHABET['vowels']);
        }
        return static::subString(\implode('', $letters), 0, $length);
    }

    public static function subString(string $str, int $start, int $length = null): string
    {
        if (true === \function_exists('mb_substr')) {
            return \mb_substr($str, $start, $length, static::HELPER_STRING_ENCODING);
        }
        return \substr($str, $start, $length);
    }

    public static function lowerCase(string $str): string
    {
        if (true === \function_exists('mb_strtolower')) {
            return \mb_strtolower($str, static::HELPER_STRING_ENCODING);
        }
        return \strtolower($str);
    }

    public static function upperCase(string $str): string
    {
        if (true === \function_exists('mb_strtoupper')) {
            return \mb_strtoupper($str, static::HELPER_STRING_ENCODING);
        }
        return \strtoupper($str);
    }

    public static function ucfirstAll(string $str, string $delimiter = '-'): string
    {
        return \implode('', \array_map('\\ucfirst', \explode($delimiter, $str)));
    }

    public static function safetize(string $str): string
    {
        return \htmlentities(static::escape($str), \ENT_QUOTES, static::HELPER_STRING_ENCODING);
    }

    public static function escape(string $str): string
    {
        return \addcslashes(\addslashes($str), "/\x00\x1a");
    }

    public static function minimize(string $str): string
    {
        return \str_replace(static::HELPER_STRING_WRAPPERS, '', \trim($str));
    }

    public static function startsWith(string $str, array $prefixes): bool
    {
        foreach ($prefixes as $prefix) {
            if (0 === \strpos($str, $prefix)) return true;
        }
        return false;
    }

    public static function escapeSpecialChar(string $str): string
    {
        return \preg_replace(static::HELPER_STRING_SPECIALCHAR_PATTERN, '\\\\$1', $str);
    }

    protected static function randomizeStrings(array $strings): string
    {
        $count = \count($strings);
        if (0 === $count) static::throwRuntimeException('Empty Strings Found');
        return $strings[\rand(0, $count - 1)];
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_STRING_ADDITIONALS;
    }
}
