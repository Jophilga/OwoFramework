<?php

namespace framework\libraries\owo\classes\Dbases;

use framework\libraries\owo\classes\Dbases\OwoDbaseConnection;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionSqlInterface;
use framework\libraries\owo\interfaces\Queries\OwoQueryExecutorInterface;
use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderSqlInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonSqlBuilderTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoDbaseConnectionSql extends OwoDbaseConnection implements OwoDbaseConnectionSqlInterface
{
    use OwoMakeCommonSqlBuilderTrait;
    use OwoMakeCommonThrowerTrait;

    public const DBASE_CONNECT_MYSQL_KEY_PRIMARY = 'PRI';

    public const DBASE_CONNECT_MYSQL_ABSTRACT = true;

    public function __construct(OwoQueryExecutorInterface $executor, array $options = [])
    {
        parent:: __construct($executor, $options);
    }

    public function getAbstract(): bool
    {
        return $this->getOption('abstract', static::DBASE_CONNECT_MYSQL_ABSTRACT);
    }

    public function truncate(string $table): bool
    {
        $builder = $this->makeTruncateBuilder($table);
        if (true !== \is_null($statement = $this->execute($builder))) {
            $statement->closeCursor();
            return true;
        }
        return fase;
    }

    public function insertMultiple(string $table, array $data, array $columns = []): array
    {
        return \array_filter(\array_map(function ($item) use ($table, $columns) {
            return $this->insertOne($table, $item, $columns);
        }, $data));
    }

    public function insertOne(string $table, array $data, array $columns = []): ?array
    {
        $builder = $this->makeInsertOneBuilder($table, $data);
        if (true !== \is_null($statement = $this->execute($builder))) {
            $statement->closeCursor();
            if (true === \is_null($id = $this->getLastInsertedId())) {
                static::throwRuntimeException('Last Inserted ID Not Found');
            }
            return $this->selectOne($table, $id, $columns);
        }
        return null;
    }

    public function selectColumnWhere(string $table, string $column, array $matches = []): array
    {
        $results = $this->selectWhere($table, $matches);
        return \array_filter(\array_map(function ($item) use ($column) {
            return $item[$column] ?? null;
        }, $results));
    }

    public function selectAll(string $table, array $columns = []): array
    {
        return $this->selectWhere($table, [], $columns);
    }

    public function selectWhere(string $table, array $matches, array $columns = []): array
    {
        $builder = $this->makeSelectWhereBuilder([$table], $matches, $columns);
        if (true !== \is_null($statement = $this->execute($builder))) {
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $statement->closeCursor();
            return $results;
        }
        return [];
    }

    public function selectOne(string $table, $id, array $columns = []): ?array
    {
        $matches = [ $this->ensureTablePrimary($table) => $id ];
        $results = $this->selectWhere($table, $matches, $columns);
        return $this->ensureNoneOrOneResultById($results, $id);
    }

    public function updateOne(string $table, $id, array $changes, array $columns = []): ?array
    {
        $matches = [ $this->ensureTablePrimary($table) => $id ];
        $results = $this->updateWhere($table, $matches, $changes, $columns);
        return $this->ensureNoneOrOneResultById($results, $id);
    }

    public function updateAll(string $table, array $changes, array $columns = []): array
    {
        return $this->updateWhere($table, [], $changes, $columns);
    }

    public function updateWhere(string $table, array $changes, array $matches, array $columns = []): array
    {
        $primary = $this->ensureTablePrimary($table);
        if (true === empty($ids = $this->selectColumnWhere($table, $primary, $matches))) {
            return [];
        }

        $builder = $this->makeUpdateWhereBuilder($table, $changes, $matches);
        if (true !== \is_null($statement = $this->execute($builder))) {
            $statement->closeCursor();
            $matches = [ \sprintf('%s:IN', $primary) => $ids ];
            return $this->selectWhere($table, $matches, $columns);
        }
        return [];
    }

    public function deleteOne(string $table, $id, array $columns = []): ?array
    {
        $matches = [ $this->ensureTablePrimary($table) => $id ];
        $results = $this->deleteWhere($table, $matches, $columns);
        return $this->ensureNoneOrOneResultById($results, $id);
    }

    public function deleteAll(string $table, array $columns = []): array
    {
        return $this->deleteWhere($table, [], $columns);
    }

    public function deleteWhere(string $table, array $matches, array $columns = []): array
    {
        $results = $this->selectWhere($table, $matches, $columns);

        $builder = $this->makeDeleteWhereBuilder($table, $matches);
        if (true !== \is_null($statement = $this->execute($builder))) {
            $statement->closeCursor();
            return $results;
        }
        return [];
    }

    public function paginateAll(string $table, int $limit, int $page = 1, array $columns = []): array
    {
        return $this->paginateWhere($table, [], $limit, $page, $columns);
    }

    public function paginateWhere(string $table, array $matches, int $limit, int $page = 1, array $columns = []): array
    {
        $total = $this->countWhere($table, $matches);
        $offset = ($page * $limit) - $limit;

        $builder = $this->makeSelectWhereBuilder([$table], $matches, $columns);
        $builder->addLimitOffsetSections($limit, $offset);

        $results = [];
        if (true !== \is_null($statement = $this->execute($builder))) {
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $statement->closeCursor();
        }

        return [
            'offset' => $offset, 'limit' => $limit,
            'current_page' => $page, 'total_pages' => \ceil($total / $limit),
            'count' => \count($results), 'total' => $total,
            'results' => $results,
        ];
    }

    public function countWhere(string $table, array $matches, string $column = '*'): ?int
    {
        $builder = $this->makeCountWhereBuilder([$table], $matches, $column);
        if (true !== \is_null($statement = $this->execute($builder))) {
            $result = $statement->fetch(\PDO::FETCH_ASSOC) ?: [];
            $count = $result[\sprintf('COUNT(%s)', $column)] ?? null;
            $statement->closeCursor();
            return $count;
        }
        return null;
    }

    public function countAll(string $table, string $column = '*'): ?int
    {
        return $this->countWhere($table, [], $column);
    }

    public function getTablePrimary(string $table, int $index = 0): ?string
    {
        $primaries = $this->getTablePrimaries($table);
        return $primaries[$index] ?? null;
    }

    public function getTablePrimaries(string $table): array
    {
        $primaries = [];
        foreach ($this->getTableDescription($table) as $column => $details) {
            $key_primary = $this->getOption('key_primary', static::DBASE_CONNECT_MYSQL_KEY_PRIMARY);
            if ($key_primary === $details['Key']) $primaries[] = $column;
        }
        return $primaries;
    }

    public function getTableDescription(string $table): array
    {
        $statement = $this->query(\sprintf('DESCRIBE %s', $table));
        if (true === \is_null($statement)) {
            throw new \RuntimeException(\sprintf('Describe Table [%s] Failed', $table));
        }

        $description = $statement->fetchAll(\PDO::FETCH_UNIQUE | \PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $description;
    }

    public function listTables(): array
    {
        $statement = $this->query('SHOW FULL TABLES');
        if (true === \is_null($statement)) {
            static::throwRuntimeException('Showing Full Tables List Failed');
        }

        $tables = $statement->fetchAll(\PDO::FETCH_UNIQUE | \PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $tables;
    }

    protected function ensureTablePrimary(string $table): string
    {
        if (true === \is_null($primary = $this->getTablePrimary($table))) {
            static::throwRuntimeException(\sprintf('Table [%s] Primary Not Found', $table));
        }
        return $primary;
    }

    protected function ensureNoneOrOneResultById(array $results, $id): ?array
    {
        if (1 < \count($results)) {
            $message = \sprintf('Multiple Results For ID [%s] Found', \strval($id));
            static::throwRuntimeException($message);
        }
        return $results[0] ?? null;
    }
}
