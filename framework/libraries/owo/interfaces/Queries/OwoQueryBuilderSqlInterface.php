<?php

namespace framework\libraries\owo\interfaces\Queries;

use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderInterface;


interface OwoQueryBuilderSqlInterface extends OwoQueryBuilderInterface
{
    public function addSelectSection(array $columns = []): self;

    public function addFromSection(array $tables): self;

    public function addWhereSection(string $column, $value, string $operator = '=', string $combiner = 'AND'): self;

    public function addWhereCombinationSection(array $matches, string $combiner = 'AND'): self;

    public function addGroupBySection(string $column): self;

    public function addHavingSection(array $matches, string $combiner = 'AND'): self;

    public function addOrderBySection(string $column): self;

    public function addLimitOffsetSections(int $limit, int $offset = 0): self;

    public function addInsertSection(string $table, array $columns = []): self;

    public function addValuesSection(array $values): self;

    public function addValuesMultipleSection(array $values): self;

    public function addOnDuplicateKeyUpdateSection(array $changes): self;

    public function addUpdateSection(string $table): self;

    public function addDeleteFromSection(string $table): self;

    public function addSetSection(array $changes): self;

    public function addJoinSection(string $type, string $table, string $lkey, string $rkey): self;

    public function addCountColumnSection(string $column = '*', string $as = null): self;

    public function addTruncateSection(string $table): self;
}
