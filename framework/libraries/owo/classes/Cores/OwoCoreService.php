<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\interfaces\Cores\OwoCoreServiceInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonContractorTrait;


abstract class OwoCoreService implements OwoCoreServiceInterface
{
    use OwoMakeCommonContractorTrait;

    abstract protected static function getInstanceName(): string;
}
