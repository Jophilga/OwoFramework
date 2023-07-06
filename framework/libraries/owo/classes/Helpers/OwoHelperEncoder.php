<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperEncoder extends OwoHelper
{
    public const HELPER_ENCODER_ADDITIONALS = [];

    public static function encodeToBase64(string $data): string
    {
        return \base64_encode($data);
    }

    public static function encodeToJson($data, bool $pretty = false): ?string
    {
        $json = \json_encode($data);
        if (true === $pretty) {
            $json = \json_encode($data, \JSON_PRETTY_PRINT);
        }
        return (false !== $json) ? $json : null;
    }

    public static function encodeToUtf8(string $data): string
    {
        return \utf8_encode($data);
    }

    public static function encodeToZlibGZIP(string $data, int $level = -1): ?string
    {
        $zlib = \zlib_encode($data, \ZLIB_ENCODING_GZIP, $level);
        return (false !== $zlib) ? $zlib : null;
    }

    public static function encodeToZlibRAW(string $data, int $level = -1): ?string
    {
        $zlib = \zlib_encode($data, \ZLIB_ENCODING_RAW, $level);
        return (false !== $zlib) ? $zlib : null;
    }

    public static function encodeToZlibDeflate(string $data, int $level = -1): ?string
    {
        $zlib = \zlib_encode($data, \ZLIB_ENCODING_DEFLATE, $level);
        return (false !== $zlib) ? $zlib : null;
    }

    public static function encodeToGzGZIP(string $data, int $level = -1): ?string
    {
        $gz = \gzencode($data, $level, \FORCE_GZIP);
        return (false !== $gz) ? $gz : null;
    }

    public static function encodeToGzDeflate(string $data, int $level = -1): ?string
    {
        $gz = \gzencode($data, $level, \FORCE_DEFLATE);
        return (false !== $gz) ? $gz : null;
    }

    public static function encodeToUrl(string $data): string
    {
        return \urlencode($data);
    }

    public static function encodeToRawurl(string $data): string
    {
        return \rawurlencode($data);
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_ENCODER_ADDITIONALS;
    }
}
