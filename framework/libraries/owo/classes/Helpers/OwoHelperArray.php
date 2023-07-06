<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoHelperArray extends OwoHelper
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_ARRAY_ADDITIONALS = [];

    public static function mergeProperly(array ...$arrays): array
    {
        $merged_arrays = [];
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) $merged_arrays[$key] = $value;
        }
        return $merged_arrays;
    }

    public static function matchesSlugOnKey(array $data, $key, array &$matches = null): bool
    {
        if (true === static::hasStringOnKey($data, $key)) {
            return (true === OwoHelperString::matchesSlug($data[$key], $matches));
        }
        return false;
    }

    public static function matchesOnKey(array $data, $key, string $pattern, array &$matches = null): bool
    {
        if (true === static::hasStringOnKey($data, $key)) {
            return (true === OwoHelperString::matches($pattern, $data[$key], $matches));
        }
        return false;
    }

    public static function equalsOnKey(array $data, $key, $value): bool
    {
        if (true === \is_null($value)) {
            return (true !== static::hasSetKey($data, $key));
        }

        if (true === static::hasSetKey($data, $key)) {
            return ($data[$key] === $value);
        }
        return false;
    }

    public static function hasEmailOnKey(array $data, $key): bool
    {
        if (true === static::hasStringOnKey($data, $key)) {
            return (false !== \filter_var($data[$key], \FILTER_VALIDATE_EMAIL));
        }
        return false;
    }

    public static function hasUrlOnKey(array $data, $key): bool
    {
        if (true === static::hasStringOnKey($data, $key)) {
            return (false !== \filter_var($data[$key], \FILTER_VALIDATE_URL));
        }
        return false;
    }

    public static function hasDateTimeOnKey(array $data, $key, string $format = 'Y-m-d'): bool
    {
        if (true === static::hasStringOnKey($data, $key)) {
            $datetime = OwoHelperString::parseToDateTime($data[$key], $format);
            if (true !== \is_null($datetime)) {
                return ($data[$key] === $datetime->format($format));
            }
        }
        return false;
    }

    public static function hasStringOnKey(array $data, $key): bool
    {
        if (true === static::hasSetKey($data, $key)) {
            return (true === \is_string($data[$key]));
        }
        return false;
    }

    public static function hasIntegerOnKey(array $data, $key): bool
    {
        if (true === static::hasSetKey($data, $key)) {
            return (true === \is_int($data[$key]));
        }
        return false;
    }

    public static function hasSetKey(array $data, $key): bool
    {
        if (true === \array_key_exists($key, $data)) {
            return (true !== \is_null($data[$key]));
        }
        return false;
    }

    public static function filterByPrefixKey(array $data, string $prefix): array
    {
        return \array_filter($data, function ($v, $k) use ($prefix) {
            return (0 === \strpos(\strval($k), $prefix));
        }, \ARRAY_FILTER_USE_BOTH);
    }

    public static function requireMinimumCountValues(array $data, int $count)
    {
        if ($count > \count($data)) {
            $message = \sprintf('Minimum Count [%d] Values Not Found', $count);
            static::throwRuntimeException($message);
        }
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_ARRAY_ADDITIONALS;
    }
}
