<?php

namespace framework\libraries\owo\classes\Queries;

use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Queries\OwoQueryExecutorInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeOptionsTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoQueryExecutor implements OwoQueryExecutorInterface
{
    use OwoTakeArrayKeyMixeOptionsTrait;

    use OwoMakeCommonThrowerTrait;

    protected $pdo = null;

    public const QUERY_EXECUTOR_PARAM_TYPES = ['boolean', 'integer', 'double', 'string', 'NULL'];

    public function __construct(\PDO $pdo, array $options = [])
    {
        $this->setPDO($pdo)->setOptions($options);
    }

    public function setPDO(\PDO $pdo): self
    {
        $this->pdo = $pdo;
        return $this;
    }

    public function getPDO(): ?\PDO
    {
        return $this->pdo;
    }

    public function transact(string $query, array $params = []): ?\PDOStatement
    {
        return $this->ensureStartedTransaction()->execute($query, $params);
    }

    public function execute(string $query, array $params = []): ?\PDOStatement
    {
        if (false !== ($statement = $this->pdo->prepare($query, $this->options))) {
            if (true === $statement->execute($this->filterParams($params))) return $statement;
            $statement->closeCursor();
        }
        return null;
    }

    public function startTransaction(): bool
    {
        if (true !== $this->inTransaction()) {
            return (true === $this->pdo->beginTransaction());
        }
        return false;
    }

    public function rollback(): bool
    {
        if (true === $this->inTransaction()) {
            return (true === $this->pdo->rollBack());
        }
        return false;
    }

    public function commit(): bool
    {
        if (true === $this->inTransaction()) {
            return (true === $this->pdo->commit());
        }
        return false;
    }

    public function inTransaction(): bool
    {
        return (true === $this->pdo->inTransaction());
    }

    public function filterParams(array $params): array
    {
        foreach ($params as $key => $value) {
            if (true !== $this->acceptsParam($value)) unset($params[$key]);
        }
        return $params;
    }

    public function acceptsParam($param): bool
    {
        $param_type = OwoHelperString::lowerCase(\gettype($param));
        return (true === \in_array($param_type, static::QUERY_EXECUTOR_PARAM_TYPES, true));
    }

    protected function ensureStartedTransaction(): self
    {
        if ((true !== $this->inTransaction()) && (true !== $this->startTransaction())) {
            static::throwRuntimeException('Transaction Start Failed');
        }
        return $this;
    }
}
