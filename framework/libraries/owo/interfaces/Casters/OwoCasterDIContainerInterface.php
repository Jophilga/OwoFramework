<?php

namespace framework\libraries\owo\interfaces\Casters;

use framework\libraries\owo\interfaces\Casters\OwoCasterInstantiatorInterface;
use framework\libraries\owo\interfaces\Commons\OwoCommonSingletonInterface;


interface OwoCasterDIContainerInterface extends OwoCasterInstantiatorInterface, OwoCommonSingletonInterface
{
    public function __call(string $name, array $arguments);

    public function get(string $name, array $args = []): ?object;

    public function construct(string $name, array $args = []): ?object;

    public function produce(string $name, array $args = []): ?object;

    public function realize(callable $resolver, callable $callback, array $args = []): ?object;

    public function invoke(string $name, ...$args);

    public function aliasSummons(string $names): self;

    public function aliasSummon(string $name, array $aliases): self;

    public function setProcedures(array $procedures): self;

    public function emptyProcedures(): self;

    public function addProcedures(array $procedures): self;

    public function addProcedure($key, callable $value): self;

    public function getProcedures(): array;

    public function hasProcedure($key): bool;

    public function removeProcedure($key): self;

    public function getProcedure($key, $default = null): ?callable;

    public function setSummons(array $summons): self;

    public function emptySummons(): self;

    public function addSummons(array $summons): self;

    public function addSummon($key, callable $value): self;

    public function getSummons(): array;

    public function hasSummon($key): bool;

    public function removeSummon($key): self;

    public function getSummon($key, $default = null): ?callable;

    public static function __callStatic(string $name, array $arguments);
}
