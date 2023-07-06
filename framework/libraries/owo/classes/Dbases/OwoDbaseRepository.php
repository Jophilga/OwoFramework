<?php

namespace framework\libraries\owo\classes\Dbases;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;
use framework\libraries\owo\interfaces\Dbases\OwoDbaseRepositoryInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringTableTrait;


class OwoDbaseRepository implements OwoDbaseRepositoryInterface
{
    use OwoTakeStringTableTrait;

    protected $connection = null;

    public function __construct(OwoDbaseConnectionInterface $connection, string $table)
    {
        $this->setConnection($connection)->setTable($table);
    }

    public function setConnection(OwoDbaseConnectionInterface $connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    public function getConnection(): ?OwoDbaseConnectionInterface
    {
        return $this->connection;
    }

    public function createMany(array $data, array $columns = []): array
    {
        return $this->connection->insertMultiple($this->table, $data, $columns);
    }

    public function createOne(array $data, array $columns = []): ?array
    {
        return $this->connection->insertOne($this->table, $data, $columns);
    }

    public function findAll(array $columns = []): array
    {
        return $this->connection->selectAll($this->table, $columns);
    }

    public function findWhere(array $matches, array $columns = []): array
    {
        return $this->connection->selectWhere($this->table, $matches, $columns);
    }

    public function findOne($id, array $columns = []): ?array
    {
        return $this->connection->selectOne($this->table, $id, $columns);
    }

    public function updateOne($id, array $changes, array $columns = []): ?array
    {
        return $this->connection->updateOne($this->table, $id, $changes, $columns);
    }

    public function updateAll(array $changes, array $columns = []): array
    {
        return $this->connection->updateAll($this->table, $changes, $columns);
    }

    public function updateWhere(array $changes, array $matches, array $columns = []): array
    {
        return $this->connection->updateWhere($this->table, $changes, $matches, $columns);
    }

    public function deleteOne($id, array $columns = []): ?array
    {
        return $this->connection->deleteOne($this->table, $id, $columns);
    }

    public function deleteAll(array $columns = []): array
    {
        return $this->connection->deleteAll($this->table, $columns);
    }

    public function deleteWhere(array $matches, array $columns = []): array
    {
        return $this->connection->deleteWhere($this->table, $matches, $columns);
    }

    public function paginateAll(int $limit, int $page = 1, array $columns = []): array
    {
        return $this->connection->paginateAll($this->table, $limit, $page, $columns);
    }

    public function paginateWhere(array $matches, int $limit, int $page = 1, array $columns = []): array
    {
        return $this->connection->paginateWhere($this->table, $matches, $limit, $page, $columns);
    }

    public function countWhere(array $matches, string $column = '*'): ?int
    {
        return $this->connection->countWhere($this->table, $matches, $column);
    }

    public function countAll(string $column = '*'): ?int
    {
        return $this->connection->countAll($this->table, $column);
    }
}
