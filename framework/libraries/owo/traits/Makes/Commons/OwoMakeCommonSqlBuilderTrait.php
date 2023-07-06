<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\classes\Queries\OwoQueryBuilderSql;

use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderSqlInterface;


trait OwoMakeCommonSqlBuilderTrait
{
    public function makeTruncateBuilder(string $table): OwoQueryBuilderSqlInterface
    {
        return $this->makeBuilderSql()->addTruncateSection($table);
    }

    public function makeInsertOneBuilder(string $table, array $data): OwoQueryBuilderSqlInterface
    {
        $builder = $this->makeBuilderSql()->addInsertSection($table, \array_keys($data));
        return $builder->addValuesSection($data);
    }

    public function makeSelectWhereBuilder(array $tables, array $matches, array $columns = []): OwoQueryBuilderSqlInterface
    {
        $builder = $this->makeBuilderSql()->addSelectSection($columns)->addFromSection($tables);
        return $builder->addWhereCombinationSection($matches);
    }

    public function makeUpdateWhereBuilder(string $table, array $changes, array $matches): OwoQueryBuilderSqlInterface
    {
        $builder = $this->makeBuilderSql()->addUpdateSection($table)->addSetSection($changes);
        return $builder->addWhereCombinationSection($matches);
    }

    public function makeDeleteWhereBuilder(string $table, array $matches): OwoQueryBuilderSqlInterface
    {
        $builder = $this->makeBuilderSql()->addDeleteFromSection($table);
        return $builder->addWhereCombinationSection($matches);
    }

    public function makeCountWhereBuilder(array $tables, array $matches, string $column = '*'): OwoQueryBuilderSqlInterface
    {
        $builder = $this->makeBuilderSql()->addCountColumnSection($column)->addFromSection($tables);
        return $builder->addWhereCombinationSection($matches);
    }

    public function makeBuilderSql(array $sections = []): OwoQueryBuilderSqlInterface
    {
        return new OwoQueryBuilderSql($sections, $this->getAbstract());
    }

    abstract public function getAbstract(): bool;
}
