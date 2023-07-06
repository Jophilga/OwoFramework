<?php

namespace framework\libraries\owo\classes\Casters;

use framework\libraries\owo\classes\Casters\OwoCasterInstantiator;
use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyCallableProceduresTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyCallableSummonsTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonSingletonTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoCasterDIContainer extends OwoCasterInstantiator implements OwoCasterDIContainerInterface
{
    use OwoTakeArrayKeyCallableSummonsTrait;
    use OwoTakeArrayKeyCallableProceduresTrait;

    use OwoMakeCommonSingletonTrait;
    use OwoMakeCommonThrowerTrait;

    public function __construct(array $registries = [], array $procedures = [], array $summons = [])
    {
        $this->setRegistries($registries)->setProcedures($procedures)->setSummons($summons);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->invoke($name, ...$arguments);
    }

    public function get(string $name, array $args = []): ?object
    {
        $resolver = [ $this, 'get', ];
        return $this->realize($resolver, function ($dicontainer) use ($name, $args) {
            $instance = $dicontainer->produce($name, $args);
            return $instance ?? $dicontainer->instantiate($name, $args);
        });
    }

    public function construct(string $name, array $args = []): ?object
    {
        $resolver = [ $this, 'resolve', ];
        return $this->realize($resolver, function ($dicontainer) use ($name, $args) {
            $instance = $dicontainer->produce($name, $args);
            return $instance ?? $dicontainer->resolve($name, $args);
        });
    }

    public function realize(callable $resolver, callable $callback, array $args = []): ?object
    {
        $current_resolver = $this->resolver;
        $this->setResolver($resolver);
        $instance = \call_user_func($callback, $this, $args);
        $this->resolver = $current_resolver;
        return $instance;
    }

    public function produce(string $name, array $args = []): ?object
    {
        $procedure = $this->getProcedure($name);
        if (true !== \is_null($procedure)) {
            return \call_user_func($procedure, $this, $args);
        }
        return null;
    }

    public function invoke(string $name, ...$args)
    {
        if (true === \is_null($summon = $this->getSummon($name))) {
            static::throwRuntimeException(\sprintf('Summon [%s] Not Found', $name));
        }
        return \call_user_func($summon, $this, ...$args);
    }

    public function aliasSummons(string $names): self
    {
        foreach ($names as $name => $aliases) $this->aliasSummon($name, $aliases);
        return $this;
    }

    public function aliasSummon(string $name, array $aliases): self
    {
        if (true !== \is_null($summon = $this->getSummon($name))) {
            foreach ($aliases as $alias) {
                $this->addSummon($alias, $summon);
            }
        }
        return $this;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return static::getSingleton()->$name(...$arguments);
    }
}
