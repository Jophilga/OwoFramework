<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\interfaces\Commons\OwoCommonSingletonInterface;
use framework\libraries\owo\interfaces\Commons\OwoCommonUtilityInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonSingletonTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonUtilityTrait;


abstract class OwoHelper implements OwoCommonSingletonInterface, OwoCommonUtilityInterface
{
    use OwoMakeCommonSingletonTrait;
    use OwoMakeCommonUtilityTrait;

    abstract protected static function getAdditionalMethods(): array;
}
