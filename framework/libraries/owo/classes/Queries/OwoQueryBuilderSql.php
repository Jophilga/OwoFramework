<?php

namespace framework\libraries\owo\classes\Queries;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelperQuery;
use framework\libraries\owo\classes\Queries\OwoQueryBuilder;

use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderSqlInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoQueryBuilderSql extends OwoQueryBuilder implements OwoQueryBuilderSqlInterface
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_QUERY_SQL_JOIN_TYPES = [
        'LEFT', 'LEFT OUTER', 'RIGHT', 'RIGHT OUTER', 'FULL', 'FULL OUTER',
        'INNER', 'CROSS', 'SELF', 'NATURAL', 'UNION',
    ];

    public function __construct(array $sections = [], bool $abstract = true)
    {
        parent::__construct($sections, $abstract);
    }

    public function addSelectSection(array $columns = []): self
    {
        return $this->assignSection('SELECT', static::getSelectColumnsSection($columns));
    }

    public function addFromSection(array $tables): self
    {
        if (true === empty($tables)) {
            static::throwInvalidArgumentException('Empty Tables List Found');
        }
        return $this->assignSection('FROM', \implode(', ', $tables));
    }

    public function addWhereSection(string $column, $value, string $operator = '=', string $combiner = 'AND'): self
    {
        $delimiter = OwoHelperQuery::HELPER_QUERY_COLUMN_OPERATOR_DELIMITER;
        $matches = [\sprintf('%s%s%s', $column, $delimiter, $operator) => $value];
        return $this->addWhereCombinationSection($matches, $combiner);
    }

    public function addWhereCombinationSection(array $matches, string $combiner = 'AND'): self
    {
        $matches = $this->prepareParams($matches);
        $condition = OwoHelperQuery::getSqlWhereCombinedCondition($matches, $combiner);
        return $this->assignSection('WHERE', $condition, $combiner);
    }

    public function addGroupBySection(string $column): self
    {
        return $this->assignSection('GROUP BY', $column);
    }

    public function addHavingSection(array $matches, string $combiner = 'AND'): self
    {
        $matches = $this->prepareParams($matches);
        $condition = OwoHelperQuery::getSqlWhereCombinedCondition($matches, $combiner);
        return $this->assignSection('HAVING', $condition, $combiner);
    }

    public function addOrderBySection(string $column): self
    {
        return $this->assignSection('ORDER BY', $column);
    }

    public function addLimitOffsetSections(int $limit, int $offset = 0): self
    {
        return $this->assignSections(['LIMIT' => $limit, 'OFFSET' => $offset]);
    }

    public function addInsertSection(string $table, array $columns = []): self
    {
        if (true !== empty($columns)) {
            $section = \sprintf('%s (%s)', $table, \implode(', ', $columns));
            return $this->assignSection('INSERT INTO', $section);
        }
        return $this->assignSection('INSERT INTO', $table);
    }

    public function addValuesSection(array $values): self
    {
        if (true === empty($values)) {
            static::throwInvalidArgumentException('Empty Values List Found');
        }

        $section = $this->getPreparedValuesSection($values);
        return $this->assignSection('VALUES', $section, \sprintf(',%s', \PHP_EOL));
    }

    public function addValuesMultipleSection(array $values): self
    {
        \array_map([$this, 'addValuesSection'], $values);
        return $this;
    }

    public function addOnDuplicateKeyUpdateSection(array $changes): self
    {
        $section = $this->getPreparedSetSection($changes);
        return $this->assignSection('ON DUPLICATE KEY UPDATE', $section);
    }

    public function addUpdateSection(string $table): self
    {
        return $this->assignSection('UPDATE', $table);
    }

    public function addSetSection(array $changes): self
    {
        return $this->assignSection('SET', $this->getPreparedSetSection($changes));
    }

    public function addDeleteFromSection(string $table): self
    {
        return $this->assignSection('DELETE FROM', $table);
    }

    public function addJoinSection(string $type, string $table, string $lkey, string $rkey): self
    {
        $type = static::ensureSqlJoinType($type);
        $section = OwoHelperQuery::getSqlJoinOperation($table, $lkey, $rkey);
        return $this->assignSection($type, $section);
    }

    public function addCountColumnSection(string $column = '*', string $as = null): self
    {
        $section = \sprintf('COUNT(%s)', $column);
        if (true !== \is_null($as)) $section = \sprintf('%s AS %s', $section, $as);
        return $this->assignSection('SELECT', $section);
    }

    public function addTruncateSection(string $table): self
    {
        return $this->assignSection('TRUNCATE', $table);
    }

    protected function getPreparedSetSection(array $matches): string
    {
        return OwoHelperQuery::getSqlSetAssignation($this->prepareParams($matches));;
    }

    protected function getPreparedValuesSection(array $values): string
    {
        return \sprintf('(%s)', \implode(', ', $this->prepareParams($values)));
    }

    protected function prepareParams(array $params): array
    {
        if (true === $this->abstract) {
            return $this->prepareAbstractedParams($params);
        }
        return $this->prepareValuedParams($params);
    }

    protected function prepareAbstractedParams(array $params): array
    {
        $prepared_params = [];
        foreach ($params as $key => $value) {
            $prepared_params[$key] = $this->prepareAbstractedParam($key, $value);
        }
        return $prepared_params;
    }

    protected function prepareValuedParams(array $params): array
    {
        $prepared_params = [];
        foreach ($params as $key => $value) {
            $prepared_params[$key] = $this->singleQuotationMarkVar($value);
        }
        return $prepared_params;
    }

    protected function prepareAbstractedParam($key, $param)
    {
        if (true === \is_null($param)) return null;

        list($column) = OwoHelperQuery::explodeSqlColumnOperator(\strval($key));
        if (true === \is_array($param)) {
            $params = [];
            foreach (\array_values($param) as $index => $value) {
                $key = \sprintf('%s_%s', $column, $index);
                $params[$index] =$this->abstractPreparedParam($key, $value);
            }
            return $params;
        }
        return $this->abstractPreparedParam($column, $param);
    }

    protected function abstractPreparedParam(string $column, $param): string
    {
        return $this->addPreparedParam($column, $param)->prefixColonMarkVar($column);
    }

    protected function addPreparedParam($key, $param): self
    {
        if (true !== $this->hasPreparedParam($key)) {
            $this->prepared_params[$key] = $param;
        }
        return $this;
    }

    protected function hasPreparedParam($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->prepared_params, $key));
    }

    protected function prefixColonMarkVar($var)
    {
        if (true === \is_null($var)) return $var;
        elseif (true === \is_array($var)) {
            return \array_map([$this, 'prefixColonMarkVar'], $var);
        }
        elseif (true === \is_bool($var)) {
            return $this->ensurePrefixColonMark(\strval(\intval($var)));
        }
        return $this->ensurePrefixColonMark(\strval($var));
    }

    protected function singleQuotationMarkVar($var)
    {
        if (true === \is_null($var)) return $var;
        elseif (true === \is_array($var)) {
            return \array_map([$this, 'singleQuotationMarkVar'], $var);
        }
        elseif (true === \is_bool($var)) {
            return $this->ensureSingleQuotationMark(\strval(\intval($var)));
        }
        return $this->ensureSingleQuotationMark(\strval($var));
    }

    protected function ensurePrefixColonMark(string $str): string
    {
        if (true !== OwoHelperString::matches('/^\:(?<inner>.*)/', $str)) {
            return \sprintf(':%s', $this->sanitize($str));
        }
        return $str;
    }

    protected function ensureSingleQuotationMark(string $str): string
    {
        if (true !== OwoHelperString::matches('/^\'(?<inner>.*)\'$/', $str)) {
            return \sprintf("'%s'", OwoHelperString::escape($str));
        }
        return $str;
    }

    protected function sanitize(string $str): string
    {
        return \preg_replace('/[^a-zA-Z0-9_]/', '_', $str);
    }

    public static function getSelectColumnsSection(array $columns): string
    {
        return (true !== empty($columns)) ? \implode(', ', $columns) : ('*');
    }

    public static function ensureSqlJoinType(string $type): string
    {
        $type = OwoHelperString::upperCase($type);
        if (true === \in_array($type, OwoHelperQuery::HELPER_QUERY_SQL_JOIN_TYPES, true)) {
            $message = \sprintf('Wrong SQL Join Type [%s] Found', $type);
            static::throwRuntimeException($message);
        }
        return $type;
    }
}
