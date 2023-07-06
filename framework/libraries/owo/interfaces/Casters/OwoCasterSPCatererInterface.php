<?php

namespace framework\libraries\owo\interfaces\Casters;

use framework\libraries\owo\interfaces\Commons\OwoCommonCaravanInterface;
use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreProviderInterface;


interface OwoCasterSPCatererInterface extends OwoCommonCaravanInterface
{
    public function supplyProviders(array $names): self;

    public function supplyProvider(string $name): self;

    public function settleProviders(array $providers): self;

    public function settleProvider($provider, string $name = null): self;

    public function setProviders(array $providers): self;

    public function emptyProviders(): self;

    public function addProviders(array $providers): self;

    public function addProvider(OwoCoreProviderInterface $provider, string $name = null): self;

    public function getProviders(): array;

    public function hasProvider(string $name): bool;

    public function getProvider(string $name, $default = null): ?OwoCoreProviderInterface;

    public function removeProvider(string $name): self;
}
