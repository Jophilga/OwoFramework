<?php

namespace framework\libraries\owo\interfaces\Cores;

use framework\libraries\owo\interfaces\Commons\OwoCommonCaravanInterface;
use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


interface OwoCoreProviderInterface extends OwoCommonCaravanInterface
{
    public function provide(): self;

    public function register(): self;
}
