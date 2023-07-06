<?php

namespace framework\libraries\owo\interfaces\Casters;


interface OwoCasterInstantiatorInterface
{
    public function aliasRegistries(string $names): self;

    public function aliasRegistry(string $name, array $aliases): self;

    public function aliasInstances(string $names): self;

    public function aliasInstance(string $name, array $aliases): self;

    public function singleton($instance, $registry = null): self;

    public function instantiate(string $name, array $args = []): ?object;

    public function employ(string $name, array $args = []): ?object;

    public function resolve($element, array $args = []): ?object;

    public function resolveParam(\ReflectionParameter $param, array $args = []);

    public function addInstances(array $instances): self;

    public function addInstance(object $instance, string $name = null): self;

    public function replaceInstance(object $instance, string $name = null): self;

    public function setInstances(array $instances): self;

    public function emptyInstances(): self;

    public function getInstances(): array;

    public function hasInstance($key): bool;

    public function removeInstance($key): self;

    public function getInstance($key, $default = null): ?object;

    public function setRegistries(array $registries): self;

    public function emptyRegistries(): self;

    public function addRegistries(array $registries): self;

    public function addRegistry($key, callable $value): self;

    public function getRegistries(): array;

    public function hasRegistry($key): bool;

    public function removeRegistry($key): self;

    public function getRegistry($key, $default = null): ?callable;

    public function setResolver(callable $resolver): self;

    public function getResolver(): ?callable;
}
