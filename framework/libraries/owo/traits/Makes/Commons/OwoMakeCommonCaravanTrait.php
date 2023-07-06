<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


trait OwoMakeCommonCaravanTrait
{
    protected $container = null;

    public function __construct(OwoCasterDIContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public function setContainer(OwoCasterDIContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function getContainer(): ?OwoCasterDIContainerInterface
    {
        return $this->container;
    }

    public function contains(string $name): bool
    {
        if (true === $this->container->hasInstance($name)) return true;
        return (true === $this->container->hasRegistry($name));
    }

    public function define($instance, $registry = null): self
    {
        if (true === \is_string($instance)) {
            if (true !== $this->contains($instance)) {
                $this->container->singleton($instance, $registry);
            }
        }
        else $this->container->singleton($instance, $registry);
        return $this;
    }

    public function retrieve(string $name, array $args = []): ?object
    {
        return $this->container->instantiate($name, $args);
    }
}
