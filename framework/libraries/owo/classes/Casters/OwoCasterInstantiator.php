<?php

namespace framework\libraries\owo\classes\Casters;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Casters\OwoCasterInstantiatorInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyObjectInstancesTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyCallableRegistriesTrait;
use framework\libraries\owo\traits\Takes\Callables\OwoTakeCallableResolerTrait;


class OwoCasterInstantiator implements OwoCasterInstantiatorInterface
{
    use OwoTakeArrayKeyObjectInstancesTrait;
    use OwoTakeArrayKeyCallableRegistriesTrait;
    use OwoTakeCallableResolerTrait;

    public function __construct(array $registries = [])
    {
        $this->setRegistries($registries);
    }

    public function aliasRegistries(string $names): self
    {
        foreach ($names as $name => $aliases) $this->aliasRegistry($name, $aliases);
        return $this;
    }

    public function aliasRegistry(string $name, array $aliases): self
    {
        if (true !== \is_null($registry = $this->getRegistry($name))) {
            foreach ($aliases as $alias) {
                $this->addRegistry($alias, $registry);
            }
        }
        return $this;
    }

    public function aliasInstances(string $names): self
    {
        foreach ($names as $name => $aliases) $this->aliasInstance($name, $aliases);
        return $this;
    }

    public function aliasInstance(string $name, array $aliases): self
    {
        if (true !== \is_null($instance = $this->getInstance($name))) {
            foreach ($aliases as $alias) {
                $this->addInstance($instance, $alias);
            }
        }
        return $this;
    }

    public function singleton($instance, $registry = null): self
    {
        if ((true === \is_string($instance)) && (true === \is_callable($registry))) {
            return $this->addRegistry($instance, $registry);
        }

        if (true === \is_object($instance)) {
            $registry = (true === \is_string($registry)) ? $registry : null;
            $this->addInstance($instance, $registry);
        }
        return $this;
    }

    public function instantiate(string $name, array $args = []): ?object
    {
        if (true === \is_null($instance = $this->getInstance($name))) {
            $instance = $this->employ($name, $args) ?? $this->resolve($name, $args);
            if (true !== \is_null($instance)) {
                $this->addInstance($instance, $name);
            }
        }
        return $instance;
    }

    public function employ(string $name, array $args = []): ?object
    {
        $registry = $this->getRegistry($name);
        if (true !== \is_null($registry)) {
            return \call_user_func($registry, $this, $args);
        }
        return null;
    }

    public function resolve($element, array $args = []): ?object
    {
        $reflection = new \ReflectionClass($element);
        if (true === $reflection->isInstantiable()) {
            if (true === \is_null($constructor = $reflection->getConstructor())) {
                return $reflection->newInstance();
            }

            $constructor_params = $constructor->getParameters();
            $param_args = \array_slice($args, \count($constructor_params));

            $arguments = [];
            foreach ($constructor_params as $key => $param) {
                if (true !== OwoHelperArray::hasSetKey($args, $key)) {
                    $arguments[] = $this->resolveParam($param, $param_args);
                    continue;
                }
                $arguments[] = $args[$key];
            }
            return $reflection->newInstanceArgs($arguments);
        }
        return null;
    }

    public function resolveParam(\ReflectionParameter $param, array $args = [])
    {
        if (true !== \is_null($type = $param->getType())) {
            if (true === \is_a($type, \ReflectionNamedType::class)) {
                if (true === $type->isBuiltin()) return $param->getDefaultValue();
            }

            $instance = \call_user_func($this->ensureResolver(), $type->getName(), $args);
            if (true !== \is_null($instance)) return $instance;
        }
        return $param->getDefaultValue();
    }

    public function addInstances(array $instances): self
    {
        foreach ($instances as $name => $instance) {
            if (true === \is_string($name)) $this->addInstance($instance, $name);
            else $this->addInstance($instance);
        }
        return $this;
    }

    public function addInstance(object $instance, string $name = null): self
    {
        $name = $name ?? \get_class($instance);
        if (true !== $this->hasInstance($name)) $this->replaceInstance($instance, $name);
        return $this;
    }

    public function replaceInstance(object $instance, string $name = null): self
    {
        $this->instances[$name ?? \get_class($instance)] = $instance;
        return $this;
    }

    protected function ensureResolver(): callable
    {
        if (true === \is_null($this->resolver)) return function () {
            return null;
        };
        return $this->resolver;
    }

    public static function newInstanceArgs($element, array $args = []): object
    {
        return (new \ReflectionClass($element))->newInstanceArgs($args);
    }

    public static function isSubOrInstanceOf($element, string $class): bool
    {
        if (true !== \is_a($element, $class, true)) {
            return (true === \is_subclass_of($element, $class, true));
        }
        return true;
    }

    public static function getClassShortName($element): string
    {
        return (new \ReflectionClass($element))->getShortName();
    }
}
