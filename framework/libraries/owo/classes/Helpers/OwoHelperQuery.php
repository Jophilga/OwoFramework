<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoHelperQuery extends OwoHelper
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_QUERY_ADDITIONALS = [];

    public const HELPER_QUERY_COLUMN_OPERATOR_DELIMITER = ':';

    public static function getSqlSetAssignation(array $matches): string
    {
        if (true === empty($matches)) {
            static::throwInvalidArgumentException('Empty Matches Assignation List Found');
        }

        $assignments = [];
        foreach ($matches as $key => $value) {
            $assignments[] = \sprintf('%s = %s', \strval($key), \strval($value));
        }
        return \implode(', ', $assignments);
    }

    public static function getSqlWhereCombinedCondition(array $matches, string $combiner = 'AND'): string
    {
        $conditions = [];
        foreach ($matches as $key => $value) {
            $conditions[] = static::getSqlWhereCondition($key, $value);
        }

        if (0 < ($count = \count($conditions))) {
            switch ($combiner) {
                case 'OR':
                    if (1 < $count) return \sprintf('(%s)', \implode(' OR ', $conditions));
                    return $conditions[0];
                break;

                case 'AND':
                    if (1 < $count) return \sprintf('(%s)', \implode(' AND ', $conditions));
                    return $conditions[0];
                break;

                default:
                    $message = \sprintf('Wrong Combiner [%s] Found', $combiner);
                    static::throwInvalidArgumentException($message);
                break;
            }
        }
        return '1';
    }

    public static function getSqlWhereCondition(string $column, $value = null): string
    {
        list($column, $operator) = static::explodeSqlColumnOperator($column);
        switch ($operator = OwoHelperString::upperCase($operator)) {
            case '=':
                if (true === \is_null($value)) return static::getSqlWhereIsNullCondition($column);
                return static::getSqlWhereEqualCondition($column, $value);
            break;

            case '<>': case '!=':
                if (true === \is_null($value)) return static::getSqlWhereIsNotNullCondition($column);
                return static::getSqlWhereNotEqualCondition($column, $value);
            break;

            case '<': case '<=': case '>': case '>=':
                return \sprintf('(%s %s %s)', $column, $operator, \strval($value));
            break;

            case 'NOT IN': case ')(':
                return static::getSqlWhereNotInCondition($column, $value);
            break;

            case 'IN': case '()':
                return static::getSqlWhereInCondition($column, $value);
            break;

            case 'NOT BETWEEN': case '][':
                return static::getSqlWhereNotBetweenCondition($column, $value);
            break;

            case 'BETWEEN': case '[]':
                return static::getSqlWhereBetweenCondition($column, $value);
            break;

            case 'LIKE': case '~': case '@':
                return static::getSqlWhereLikeCondition($column, $value);
            break;

            case 'NOT LIKE': case '!~': case '!@':
                return static::getSqlWhereNotLikeCondition($column, $value);
            break;

            default:
                static::throwDomainException(\sprintf('Wrong Operator [%s]', $operator));
            break;
        }
    }

    public static function getSqlWhereEqualCondition(string $column, string $value): string
    {
        return \sprintf('(%s = %s)', $column, $value);
    }

    public static function getSqlWhereNotEqualCondition(string $column, string $value): string
    {
        return \sprintf('(%s != %s)', $column, $value);
    }

    public static function getSqlWhereInCondition(string $column, array $values): string
    {
        static::ensureNotEmptyValues($values);
        return \sprintf('(%s IN (%s))', $column, \implode(', ', $values));
    }

    public static function getSqlWhereNotInCondition(string $column, array $values): string
    {
        static::ensureNotEmptyValues($values);
        return \sprintf('(%s NOT IN (%s))', $column, \implode(', ', $values));
    }

    public static function getSqlWhereNotBetweenCondition(string $column, array $values): string
    {
        static::requireMinimumCountValues($values, 2);
        $values = \array_values($values);
        $args = [ $column, \strval($values[0]), \strval($values[1]), ];
        return \sprintf('(%s NOT BETWEEN %s AND %s)', ...$args);
    }

    public static function getSqlWhereBetweenCondition(string $column, array $values): string
    {
        static::requireMinimumCountValues($values, 2);
        $values = \array_values($values);
        $args = [ $column, \strval($values[0]), \strval($values[1]), ];
        return \sprintf('(%s BETWEEN %s AND %s)', ...$args);
    }

    public static function getSqlWhereLikeCondition(string $column, string $value): string
    {
        return \sprintf('(%s LIKE %s)', $column, $value);
    }

    public static function getSqlWhereNotLikeCondition(string $column, string $value): string
    {
        return \sprintf('(%s NOT LIKE %s)', $column, $value);
    }

    public static function getSqlWhereIsNullCondition(string $column): string
    {
        return \sprintf('(%s IS NULL)', $column);
    }

    public static function getSqlWhereIsNotNullCondition(string $column): string
    {
        return \sprintf('(%s IS NOT NULL)', $column);
    }

    public static function getSqlJoinOperation(string $table, string $lkey, string $rkey): string
    {
        return \sprintf('%s ON %s = %s', $table, $lkey, $rkey);
    }

    public static function explodeSqlColumnOperator(string $column): array
    {
        $fragents = \explode(static::HELPER_QUERY_COLUMN_OPERATOR_DELIMITER, $column, 2);
        if (true !== OwoHelperArray::hasSetKey($fragents, 1)) $fragents[1] = ('=');
        return \array_map('\\trim', $fragents);
    }

    protected static function ensureNotEmptyValues(array $values)
    {
        if (true === empty($values)) {
            static::throwInvalidArgumentException('Empty Values List Found');
        }
    }

    protected static function requireMinimumCountValues(array $values, int $minimum)
    {
        if ($minimum > \count($values)) {
            $message = \sprintf('Less Than [%s] Values List Found', $minimum);
            static::throwInvalidArgumentException($message);
        }
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_QUERY_ADDITIONALS;
    }
}
