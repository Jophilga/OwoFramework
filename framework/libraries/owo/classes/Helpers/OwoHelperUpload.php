<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperUpload extends OwoHelper
{
    public const HELPER_UPLOAD_KEYS = [ 'name', 'type', 'tmp_name', 'error', 'size', ];

    public const HELPER_UPLOAD_ADDITIONALS = [];

    public static function getNormalizedUploadsFromGlobals(): array
    {
        return static::normalizeUploads(static::getUploadsFromGlobals());
    }

    public static function normalizeUploads(array $uploads): array
    {
        $normalized_uploads = [];
        foreach ($uploads as $label => $data) {
            if ((true === OwoHelperArray::hasSetKey($data, 'name')) && (true === \is_array($data['name']))) {
                $normalized_uploads[$label] = static::normalizeUploadsMultiple($data);
            }
            else $normalized_uploads[$label] = $data;
        }
        return $normalized_uploads;
    }

    public static function normalizeUploadsMultiple(array $uploads): array
    {
        $normalized_uploads = [];
        foreach (static::HELPER_UPLOAD_KEYS as $k) {
            \array_walk_recursive($uploads[$k], function (&$value, $key, $agrg) {
                $value = [$arg => $value];
            }, $k);
            $normalized_uploads = \array_replace_recursive($normalized_uploads, $uploads[$k]);
        }
        return $normalized_uploads;
    }

    public static function getUploadsFromGlobals(): array
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) return $_FILES;
        return [];
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_UPLOAD_ADDITIONALS;
    }
}
