<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperDecoder extends OwoHelper
{
    public const HELPER_DECODER_ADDITIONALS = [];

    public static function decodeBase64(string $data): ?string
    {
        if (false !== ($decode = \base64_decode($data))) {
            return $decode;
        }
        return null;
    }

    public static function decodeJsonToObject(string $data): ?object
    {
        return \json_decode($data, false);
    }

    public static function decodeJsonToArray(string $data): ?array
    {
        return \json_decode($data, true);
    }

    public static function decodeUtf8(string $data): string
    {
        return \utf8_decode($data);
    }

    public static function decodeZlib(string $data): ?string
    {
        if (false !== ($decode = \zlib_decode($data))) {
            return $decode;
        }
        return null;
    }

    public static function decodeGz(string $data): ?string
    {
        if (false !== ($decode = \gzdecode($data))) {
            return $decode;
        }
        return null;
    }

    public static function decodeUrl(string $data): string
    {
        return \urldecode($data);
    }

    public static function decodeRawurl(string $data): string
    {
        return \rawurldecode($data);
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_DECODER_ADDITIONALS;
    }
}
