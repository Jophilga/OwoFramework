<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


interface OwoCommonCapsulatorInterface
{
    public static function createMany(array $data, array $columns = []): array;

    public static function createOne(array $data, array $columns = []): ?object;

    public static function findAll(array $columns = []): array;

    public static function findWhere(array $matches, array $columns = []): array;

    public static function findOne($id, array $columns = []): ?object;

    public static function updateOne($id, array $changes, array $columns = []): ?object;

    public static function updateAll(array $changes, array $columns = []): array;

    public static function updateWhere(array $changes, array $matches, array $columns = []): array;

    public static function deleteOne($id, array $columns = []): ?object;

    public static function deleteAll(array $columns = []): array;

    public static function deleteWhere(array $matches, array $columns = []): array;

    public static function paginateAll(int $limit, int $page = 1, array $columns = []): array;

    public static function paginateWhere(array $matches, int $limit, int $page = 1, array $columns = []): array;

    public static function countWhere(array $matches, string $column = '*'): ?int;

    public static function countAll(string $column = '*'): ?int;

    public static function setDBConnection(OwoDbaseConnectionInterface $dbconnection);

    public static function getDBConnection(): ?OwoDbaseConnectionInterface;

    public static function getRepositoryClassName(): string;
}
