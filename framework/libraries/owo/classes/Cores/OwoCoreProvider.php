<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreProviderInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonCaravanTrait;


abstract class OwoCoreProvider implements OwoCoreProviderInterface
{
    use OwoMakeCommonCaravanTrait;

    public function __construct(OwoCasterDIContainerInterface $container)
    {
        $this->setContainer($container);
    }

    abstract public function provide(): self;

    abstract public function register(): self;
}
