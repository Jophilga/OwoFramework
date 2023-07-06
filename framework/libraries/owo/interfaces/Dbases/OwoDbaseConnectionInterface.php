<?php

namespace framework\libraries\owo\interfaces\Dbases;

use framework\libraries\owo\interfaces\Queries\OwoQueryExecutorInterface;
use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderInterface;


interface OwoDbaseConnectionInterface
{
    public function setExecutor(OwoQueryExecutorInterface $executor): self;

    public function getExecutor(): ?OwoQueryExecutorInterface;
    
    public function getLastInsertedId(string $name = null): ?string;

    public function execute(OwoQueryBuilderInterface $builder, bool $transact = false): ?\PDOStatement;

    public function query(string $query, array $params = [], bool $transact = false): ?\PDOStatement;

    public function insertMultiple(string $table, array $data, array $columns = []): array;

    public function insertOne(string $table, array $data, array $columns = []): ?array;

    public function selectColumnWhere(string $table, string $column, array $matches = []): array;

    public function selectAll(string $table, array $columns = []): array;

    public function selectWhere(string $table, array $matches, array $columns = []): array;

    public function selectOne(string $table, $id, array $columns = []): ?array;

    public function updateOne(string $table, $id, array $changes, array $columns = []): ?array;

    public function updateAll(string $table, array $changes, array $columns = []): array;

    public function updateWhere(string $table, array $changes, array $matches, array $columns = []): array;

    public function deleteOne(string $table, $id, array $columns = []): ?array;

    public function deleteAll(string $table, array $columns = []): array;

    public function deleteWhere(string $table, array $matches, array $columns = []): array;

    public function paginateAll(string $table, int $limit, int $page = 1, array $columns = []): array;

    public function paginateWhere(string $table, array $matches, int $limit, int $page = 1, array $columns = []): array;

    public function countWhere(string $table, array $matches, string $column = '*'): ?int;

    public function countAll(string $table, string $column = '*'): ?int;

    public function getTablePrimary(string $table, int $index = 0): ?string;

    public function getTablePrimaries(string $table): array;

    public function getTableDescription(string $table): array;

    public function listTables(): array;
}
