<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


interface OwoCommonCaravanInterface
{
    public function setContainer(OwoCasterDIContainerInterface $container): self;

    public function getContainer(): ?OwoCasterDIContainerInterface;

    public function contains(string $name): bool;

    public function define($instance, $registry = null): self;

    public function retrieve(string $name, array $args = []): ?object;
}
