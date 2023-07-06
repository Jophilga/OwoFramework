<?php

namespace framework\libraries\owo\interfaces\Dbases;

use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderSqlMakerInterface;
use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


interface OwoDbaseConnectionSqlInterface extends OwoDbaseConnectionInterface, OwoQueryBuilderSqlMakerInterface
{
    public function truncate(string $table): bool;

    public function getAbstract(): bool;
}
