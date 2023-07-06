<?php

namespace framework\libraries\owo\interfaces\Dbases;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


interface OwoDbaseRepositoryInterface
{
    public function setConnection(OwoDbaseConnectionInterface $connection): self;

    public function getConnection(): ?OwoDbaseConnectionInterface;

    public function createMany(array $data, array $columns = []): array;

    public function createOne(array $data, array $columns = []): ?array;

    public function findAll(array $columns = []): array;

    public function findWhere(array $matches, array $columns = []): array;

    public function findOne($id, array $columns = []): ?array;

    public function updateOne($id, array $changes, array $columns = []): ?array;

    public function updateAll(array $changes, array $columns = []): array;

    public function updateWhere(array $changes, array $matches, array $columns = []): array;

    public function deleteOne($id, array $columns = []): ?array;

    public function deleteAll(array $columns = []): array;

    public function deleteWhere(array $matches, array $columns = []): array;

    public function paginateAll(int $limit, int $page = 1, array $columns = []): array;

    public function paginateWhere(array $matches, int $limit, int $page = 1, array $columns = []): array;

    public function countWhere(array $matches, string $column = '*'): ?int;

    public function countAll(string $column = '*'): ?int;

    public function setTable(string $table): self;

    public function getTable(): ?string;
}
