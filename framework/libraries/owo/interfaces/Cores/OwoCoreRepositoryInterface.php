<?php

namespace framework\libraries\owo\interfaces\Cores;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


interface OwoCoreRepositoryInterface
{
    public function setConnection(OwoDbaseConnectionInterface $connection): self;

    public function getConnection(): ?OwoDbaseConnectionInterface;

    public function createMany(array $data, array $columns = []): array;

    public function createOne(array $data, array $columns = []): ?object;

    public function findAll(array $columns = []): array;

    public function findWhere(array $matches, array $columns = []): array;

    public function findOne($id, array $columns = []): ?object;

    public function updateOne($id, array $changes, array $columns = []): ?object;

    public function updateAll(array $changes, array $columns = []): array;

    public function updateWhere(array $changes, array $matches, array $columns = []): array;

    public function deleteOne($id, array $columns = []): ?object;

    public function deleteAll(array $columns = []): array;

    public function deleteWhere(array $matches, array $columns = []): array;

    public function paginateAll(int $limit, int $page = 1, array $columns = []): array;

    public function paginateWhere(array $matches, int $limit, int $page = 1, array $columns = []): array;

    public function countWhere(array $matches, string $column = '*'): ?int;

    public function countAll(string $column = '*'): ?int;

    public static function constructMany(array $entries): array;

    public static function constructOne(array $entry): object;

    public static function getTable(): string;
}
