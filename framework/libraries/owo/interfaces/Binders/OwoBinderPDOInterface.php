<?php

namespace framework\libraries\owo\interfaces\Binders;

use framework\libraries\owo\interfaces\Binders\OwoBinderChannelInterface;


interface OwoBinderPDOInterface extends OwoBinderChannelInterface
{
    public function setParamDriver(string $driver): self;

    public function getParamDriver($default = null): ?string;

    public function getDSN(): string;

    public function getDSNMySql(): string;

    public function getDSNPostgreSql(): string;

    public function getDSNSqlite(): string;

    public function usesDriverMySql(): bool;

    public function usesDriverPostgreSql(): bool;

    public function usesDriverSqlite(): bool;

    public function usesDriver(string $driver): bool;

    public function getPDO(): \PDO;
}
