<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperPath extends OwoHelper
{
    public const HELPER_PATH_ADDITIONALS = [];

    public static function makepath(string ...$paths): string
    {
        return \implode('/', \array_map(function (string $path) {
            return \trim($path, '/');
        }, $paths));
    }

    public static function details(string $path): array
    {
        return \pathinfo($path);
    }

    public static function dirname(string $path): string
    {
        return \pathinfo($path, \PATHINFO_DIRNAME);
    }

    public static function basename(string $path): string
    {
        return \pathinfo($path, \PATHINFO_BASENAME);
    }

    public static function extension(string $path): string
    {
        return \pathinfo($path, \PATHINFO_EXTENSION);
    }

    public static function filename(string $path): string
    {
        return \pathinfo($path, \PATHINFO_FILENAME);
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_PATH_ADDITIONALS;
    }
}
