<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelperDecoder;
use framework\libraries\owo\classes\Helpers\OwoHelper;


class OwoHelperInput extends OwoHelper
{
    public const HELPER_INPUT_ADDITIONALS = [];

    public static function getInputsFromGlobals(): array
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) return $_POST;
        return static::getInputsFromInputPHP();
    }

    public static function getInputsFromInputPHP(): array
    {
        if (false !== ($contents = \file_get_contents('php://input'))) {
            if (true === \is_null($inputs = OwoHelperDecoder::decodeJsonToArray($contents))) {
                $inputs = OwoHelperString::parseToArray($contents);
            }
            return $inputs;
        }
        return [];
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_INPUT_ADDITIONALS;
    }
}
