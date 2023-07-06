<?php

namespace framework\libraries\owo\interfaces\Queries;

use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderSqlInterface;


interface OwoQueryBuilderSqlMakerInterface
{
    public function makeTruncateBuilder(string $table): OwoQueryBuilderSqlInterface;

    public function makeInsertOneBuilder(string $table, array $data): OwoQueryBuilderSqlInterface;

    public function makeSelectWhereBuilder(array $tables, array $matches, array $columns = []): OwoQueryBuilderSqlInterface;

    public function makeUpdateWhereBuilder(string $table, array $changes, array $matches): OwoQueryBuilderSqlInterface;

    public function makeDeleteWhereBuilder(string $table, array $matches): OwoQueryBuilderSqlInterface;

    public function makeCountWhereBuilder(array $tables, array $matches, string $column = '*'): OwoQueryBuilderSqlInterface;

    public function makeBuilderSql(array $sections = []): OwoQueryBuilderSqlInterface;
}
