<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreRepositoryInterface;


abstract class OwoCoreRepository implements OwoCoreRepositoryInterface
{
    protected $connection = null;

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        $this->setConnection($connection);
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
        $results = $this->connection->insertMultiple(static::getTable(), $data, $columns);
        return static::constructMany($results);
    }

    public function createOne(array $data, array $columns = []): ?object
    {
        $result = $this->connection->insertOne(static::getTable(), $data, $columns);
        if (true !== \is_null($result)) {
            return static::constructOne($result);
        }
        return null;
    }

    public function findAll(array $columns = []): array
    {
        $results = $this->connection->selectAll(static::getTable(), $columns);
        return static::constructMany($results);
    }

    public function findWhere(array $matches, array $columns = []): array
    {
        $results = $this->connection->selectWhere(static::getTable(), $matches, $columns);
        return static::constructMany($results);
    }

    public function findOne($id, array $columns = []): ?object
    {
        $result = $this->connection->selectOne(static::getTable(), $id, $columns);
        if (true !== \is_null($result)) {
            return static::constructOne($result);
        }
        return null;
    }

    public function updateOne($id, array $changes, array $columns = []): ?object
    {
        $result = $this->connection->updateOne(static::getTable(), $id, $changes, $columns);
        if (true !== \is_null($result)) {
            return static::constructOne($result);
        }
        return null;
    }

    public function updateAll(array $changes, array $columns = []): array
    {
        $results = $this->connection->updateAll(static::getTable(), $changes, $columns);
        return static::constructMany($results);
    }

    public function updateWhere(array $changes, array $matches, array $columns = []): array
    {
        $results = $this->connection->updateWhere(static::getTable(), $changes, $matches, $columns);
        return static::constructMany($results);
    }

    public function deleteOne($id, array $columns = []): ?object
    {
        $result = $this->connection->deleteOne(static::getTable(), $id, $columns);
        if (true !== \is_null($result)) {
            return static::constructOne($result);
        }
        return null;
    }

    public function deleteAll(array $columns = []): array
    {
        $results = $this->connection->deleteAll(static::getTable(), $columns);
        return static::constructMany($results);
    }

    public function deleteWhere(array $matches, array $columns = []): array
    {
        $results = $this->connection->deleteWhere(static::getTable(), $matches, $columns);
        return static::constructMany($results);
    }

    public function paginateAll(int $limit, int $page = 1, array $columns = []): array
    {
        $results = $this->connection->paginateAll(static::getTable(), $limit, $page, $columns);
        $results['results'] = static::constructMany($results['results'] ?? []);
        return $results;
    }

    public function paginateWhere(array $matches, int $limit, int $page = 1, array $columns = []): array
    {
        $results = $this->connection->paginateWhere(static::getTable(), $matches, $limit, $page, $columns);
        $results['results'] = static::constructMany($results['results'] ?? []);
        return $results;
    }

    public function countWhere(array $matches, string $column = '*'): ?int
    {
        return $this->connection->countWhere(static::getTable(), $matches, $column);
    }

    public function countAll(string $column = '*'): ?int
    {
        return $this->connection->countAll(static::getTable(), $column);
    }

    public static function constructMany(array $entries): array
    {
        return \array_map([static::class, 'constructOne'], $entries);
    }

    abstract public static function constructOne(array $entry): object;

    abstract public static function getTable(): string;
}
