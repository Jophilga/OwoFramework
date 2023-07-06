<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreRepositoryInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


trait OwoMakeCommonCapsulatorTrait
{
    use OwoMakeCommonThrowerTrait;

    protected static $dbconnection = null;

    protected static function makeRepository(): OwoCoreRepositoryInterface
    {
        $repository = static::getRepositoryClassName();
        return new $repository(static::ensureDBConnection());
    }

    protected static function ensureDBConnection(): OwoDbaseConnectionInterface
    {
        if (true === \is_null($dbconnection = static::getDBConnection())) {
            static::throwRuntimeException('Static DB Connection Instance Not Found');
        }
        return $dbconnection;
    }

    public static function createMany(array $data, array $columns = []): array
    {
        return static::makeRepository()->createMany($data, $columns);
    }

    public static function createOne(array $data, array $columns = []): ?object
    {
        return static::makeRepository()->createOne($data, $columns);
    }

    public static function findAll(array $columns = []): array
    {
        return static::makeRepository()->findAll($columns);
    }

    public static function findWhere(array $matches, array $columns = []): array
    {
        return static::makeRepository()->findWhere($matches, $columns);
    }

    public static function findOne($id, array $columns = []): ?object
    {
        return static::makeRepository()->findOne($id, $columns);
    }

    public static function updateOne($id, array $changes, array $columns = []): ?object
    {
        return static::makeRepository()->updateOne($id, $changes, $columns);
    }

    public static function updateAll(array $changes, array $columns = []): array
    {
        return static::makeRepository()->updateAll($changes, $columns);
    }

    public static function updateWhere(array $changes, array $matches, array $columns = []): array
    {
        return static::makeRepository()->updateWhere($changes, $matches, $columns);
    }

    public static function deleteOne($id, array $columns = []): ?object
    {
        return static::makeRepository()->deleteOne($id, $columns);
    }

    public static function deleteAll(array $columns = []): array
    {
        return static::makeRepository()->deleteAll($columns);
    }

    public static function deleteWhere(array $matches, array $columns = []): array
    {
        return static::makeRepository()->deleteWhere($matches, $columns);
    }

    public static function paginateAll(int $limit, int $page = 1, array $columns = []): array
    {
        return static::makeRepository()->paginateAll($limit, $page, $columns);
    }

    public static function paginateWhere(array $matches, int $limit, int $page = 1, array $columns = []): array
    {
        return static::makeRepository()->paginateWhere($matches, $limit, $page, $columns);
    }

    public static function countWhere(array $matches, string $column = '*'): ?int
    {
        return static::makeRepository()->countWhere($matches, $column);
    }

    public static function countAll(string $column = '*'): ?int
    {
        return static::makeRepository()->countAll($column);
    }

    public static function setDBConnection(OwoDbaseConnectionInterface $dbconnection)
    {
        static::$dbconnection = $dbconnection;
    }

    public static function getDBConnection(): ?OwoDbaseConnectionInterface
    {
        return static::$dbconnection;
    }

    abstract public static function getRepositoryClassName(): string;
}
