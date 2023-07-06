<?php

namespace framework\libraries\owo\classes\Casters;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;
use framework\libraries\owo\interfaces\Casters\OwoCasterSPCatererInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreProviderInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonCaravanTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoCasterSPCaterer implements OwoCasterSPCatererInterface
{
    use OwoMakeCommonThrowerTrait;
    use OwoMakeCommonCaravanTrait;

    protected $providers = [];
    protected $container = null;

    public function __construct(OwoCasterDIContainerInterface $container, array $providers = [])
    {
        $this->setContainer($container)->settleProviders($providers);
    }

    public function supplyProviders(array $names): self
    {
        foreach ($names as $name) $this->supplyProvider($name);
        return $this;
    }

    public function supplyProvider(string $name): self
    {
        if (true === \is_null($provider = $this->getProvider($name))) {
            static::throwRuntimeException(\sprintf('Provider [%s] Not Found', $name));
        }
        return $this->supply($provider);
    }

    public function settleProviders(array $providers): self
    {
        foreach ($providers as $name => $provider) {
            if (true === \is_string($name)) $this->settleProvider($provider, $name);
            else $this->settleProvider($provider);
        }
        return $this;
    }

    public function settleProvider($provider, string $name = null): self
    {
        if (true !== \is_subclass_of($provider, OwoCoreProviderInterface::class, true)) {
            static::throwRuntimeException('Wrong Type Provider Found');
        }

        if (true === \is_string($provider)) {
            $provider = new $provider($this->container);
        }
        return $this->addProvider($provider, $name);
    }

    public function setProviders(array $providers): self
    {
        return $this->emptyProviders()->addProviders($providers);
    }

    public function emptyProviders(): self
    {
        $this->providers = [];
        return $this;
    }

    public function addProviders(array $providers): self
    {
        foreach ($providers as $name => $provider) {
            if (true === \is_string($name)) $this->addProvider($provider, $name);
            else $this->addProvider($provider);
        }
        return $this;
    }

    public function addProvider(OwoCoreProviderInterface $provider, string $name = null): self
    {
        $this->providers[$name ?? \get_class($provider)] = $provider->register();
        return $this;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function hasProvider(string $name): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->providers, $name));
    }

    public function getProvider(string $name, $default = null): ?OwoCoreProviderInterface
    {
        return $this->providers[$name] ?? $default;
    }

    public function removeProvider(string $name): self
    {
        unset($this->providers[$name]);
        return $this;
    }

    protected function supply(OwoCoreProviderInterface $provider): self
    {
        $provider->provide();
        return $this;
    }

    protected function register(OwoCoreProviderInterface $provider): self
    {
        $provider->register();
        return $this;
    }
}
