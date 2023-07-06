<?php

namespace framework\libraries\owo\classes\Dbases;

use framework\libraries\owo\interfaces\Queries\OwoQueryExecutorInterface;
use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderInterface;
use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeOptionsTrait;


abstract class OwoDbaseConnection implements OwoDbaseConnectionInterface
{
    use OwoTakeArrayKeyMixeOptionsTrait;

    protected $executor = null;

    public function __construct(OwoQueryExecutorInterface $executor, array $options = [])
    {
        $this->setExecutor($executor)->setOptions($options);
    }

    public function setExecutor(OwoQueryExecutorInterface $executor): self
    {
        $this->executor = $executor;
        return $this;
    }

    public function getExecutor(): ?OwoQueryExecutorInterface
    {
        return $this->executor;
    }

    public function getLastInsertedId(string $name = null): ?string
    {
        $id = $this->executor->getPDO()->lastInsertId($name);
        return (false !== $id) ? \strval($id) : null;
    }

    public function execute(OwoQueryBuilderInterface $builder, bool $transact = false): ?\PDOStatement
    {
        return $this->query($builder->getQuery(), $builder->getPreparedParams(), $transact);
    }

    public function query(string $query, array $params = [], bool $transact = false): ?\PDOStatement
    {
        if (true === $transact) return $this->executor->transact($query, $params);
        return $this->executor->execute($query, $params);
    }

    abstract public function insertMultiple(string $table, array $data, array $columns = []): array;

    abstract public function insertOne(string $table, array $data, array $columns = []): ?array;

    abstract public function selectColumnWhere(string $table, string $column, array $matches = []): array;

    abstract public function selectAll(string $table, array $columns = []): array;

    abstract public function selectWhere(string $table, array $matches, array $columns = []): array;

    abstract public function selectOne(string $table, $id, array $columns = []): ?array;

    abstract public function updateOne(string $table, $id, array $changes, array $columns = []): ?array;

    abstract public function updateAll(string $table, array $changes, array $columns = []): array;

    abstract public function updateWhere(string $table, array $changes, array $matches, array $columns = []): array;

    abstract public function deleteOne(string $table, $id, array $columns = []): ?array;

    abstract public function deleteAll(string $table, array $columns = []): array;

    abstract public function deleteWhere(string $table, array $matches, array $columns = []): array;

    abstract public function paginateAll(string $table, int $limit, int $page = 1, array $columns = []): array;

    abstract public function paginateWhere(string $table, array $matches, int $limit, int $page = 1, array $columns = []): array;

    abstract public function countWhere(string $table, array $matches, string $column = '*'): ?int;

    abstract public function countAll(string $table, string $column = '*'): ?int;

    abstract public function getTablePrimary(string $table, int $index = 0): ?string;

    abstract public function getTablePrimaries(string $table): array;

    abstract public function getTableDescription(string $table): array;

    abstract public function listTables(): array;
}
