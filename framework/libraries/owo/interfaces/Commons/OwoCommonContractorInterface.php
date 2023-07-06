<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


interface OwoCommonContractorInterface
{
    public static function setDIContainer(OwoCasterDIContainerInterface $dicontainer);

    public static function getDIContainer(): ?OwoCasterDIContainerInterface;

    public static function setInstance(object $instance);

    public static function getInstance(): self;
}
