<?php

namespace framework\libraries\owo\interfaces\Queries;


interface OwoQueryExecutorInterface
{
    public function transact(string $query, array $params = []): ?\PDOStatement;

    public function execute(string $query, array $params = []): ?\PDOStatement;

    public function startTransaction(): bool;

    public function rollback(): bool;

    public function commit(): bool;

    public function inTransaction(): bool;

    public function filterParams(array $params): array;

    public function acceptsParam($param): bool;

    public function setPDO(\PDO $pdo): self;

    public function getPDO(): ?\PDO;

    public function setOptions(array $options): self;

    public function emptyOptions(): self;

    public function addOptions(array $options): self;

    public function addOption($key, $value): self;

    public function getOptions(): array;

    public function hasOption($key): bool;

    public function removeOption($key): self;

    public function getOption($key, $default = null);
}
