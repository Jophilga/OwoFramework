<?php

namespace application\services\owo;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Cores\OwoCoreService;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoCrudatorService extends OwoCoreService
{
    use OwoMakeCommonThrowerTrait;

    public const CRUDATOR_SERVICE_INSTANCE_NAME = 'crudator';

    public const CRUDATOR_SERVICE_METHODS = ['POST', 'GET', 'POST', 'PATCH', 'DELETE'];

    public const CRUDATOR_SERVICE_DEFAULT_PAGE = 1;

    public const CRUDATOR_SERVICE_DEFAULT_LIMIT = 50;

    protected $connection = null;

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        $this->setConnection($connection);
    }

    public function setConnection(OwoDbaseConnectionInterface $connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    public function getConnection(): ?OwoDbaseConnectionInterface
    {
        return $this->connection;
    }

    public function handleCrudRequest(OwoServerRequestInterface $request): ?array
    {
        switch ($method = $request->getMethod()) {
            case 'POST':
                $table = static::ensureTable($inputs = $request->getInputs());
                return $this->createOne($table, $inputs, $request->params());
            break;
            case 'GET':
                $table = static::ensureTable($params = $request->params());
                if (true === OwoHelperArray::hasSetKey($params, 'id')) {
                    return $this->retrieveOne($table, $params['id'], $params);
                }
                return $this->retrieveAll($table, $params);
            break;
            case 'PUT': case 'PATCH':
                $id = static::ensureId($inputs = $request->getInputs());
                $table = static::ensureTable($inputs);
                return $this->updateOne($table, $id, $inputs, $request->params());
            break;
            case 'DELETE':
                $id = static::ensureId($inputs = $request->getInputs());
                $table = static::ensureTable($inputs);
                return $this->deleteOne($table, $id, $request->params());
            break;
            default: static::throwUnsupportedCrudMethodFound($method); break;
        }
    }

    public function createOne(string $table, array $data, array $selectors = []): ?array
    {
        $selectors = $this->prepareSelectors($selectors);
        return $this->connection->insertOne($table, $data, $selectors['colums']);
    }

    public function retrieveAll(string $table, array $selectors = []): array
    {
        $selectors = $this->prepareSelectors($selectors);
        list($matches, $limit, $page, $columns) = [
            $selectors['matches'], $selectors['limit'], $selectors['page'], $selectors['columns'],
        ];
        return $this->connection->paginateWhere($table, $matches, $limit, $page, $columns);
    }

    public function retrieveOne(string $table, $id, array $selectors = []): array
    {
        $selectors = $this->prepareSelectors($selectors);
        return $this->connection->selectOne($table, $id, $selectors['colums']);
    }

    public function updateOne(string $table, $id, array $changes, array $selectors = []): array
    {
        $selectors = $this->prepareSelectors($selectors);
        return $this->connection->updateOne($table, $id, $changes, $selectors['columns']);
    }

    public function deleteOne(string $table, $id, array $selectors = []): array
    {
        $selectors = $this->prepareSelectors($selectors);
        return $this->connection->deleteOne($table, $id, $selectors['columns']);
    }

    public function isCrudMethod(string $method): bool
    {
        return (true === \in_array($method, static::CRUDATOR_SERVICE_METHODS, true));
    }

    protected static function ensureTable(array $data)
    {
        if (true !== OwoHelperArray::hasSetKey($data, 'table')) {
            static::throwTargetTableNotFound();
        }
        return $data['table'];
    }

    protected static function ensureId(array $data)
    {
        if (true !== OwoHelperArray::hasSetKey($data, 'id')) {
            static::throwTargetIdNotFound();
        }
        return $data['id'];
    }

    protected static function prepareSelectors(array $selectors): array
    {
        $prepared_selectors = [];
        if (true === OwoHelperArray::hasSetKey($selectors, 'colums')) {
            if (true === \is_array($selectors['colums'])) {
                $prepared_selectors['colums'] = \array_map('\\trim', $selectors['colums']);
            }
            elseif (true === \is_string($selectors['colums'])) {
                $colums = \array_map('\\trim', \explode(',', $selectors['colums']));
                $prepared_selectors['colums'] = $colums;
            }
            else $prepared_selectors['colums'] = [];
        }
        else $prepared_selectors['colums'] = [];
        unset($selectors['colums']);

        if (true !== OwoHelperArray::hasSetKey($selectors, 'page')) {
            $prepared_selectors['page'] = static::CRUDATOR_SERVICE_DEFAULT_PAGE;
        }
        else $prepared_selectors['page'] = \intval($selectors['page']);
        unset($selectors['page']);

        if (true !== OwoHelperArray::hasSetKey($selectors, 'limit')) {
            $prepared_selectors['limit'] = static::CRUDATOR_SERVICE_DEFAULT_LIMIT;
        }
        else $prepared_selectors['limit'] = \intval($selectors['limit']);
        unset($selectors['limit']);

        $prepared_selectors['matches'] = $selectors;
        return $prepared_selectors;
    }

    protected static function throwTargetTableNotFound()
    {
        static::throwRuntimeException('Target Table Not Found');
    }

    protected static function throwTargetIdNotFound()
    {
        static::throwRuntimeException('Target ID Not Found');
    }

    protected static function throwUnsupportedCrudMethodFound(string $method)
    {
        $message = \sprintf('Unsupported CRUD Method [%s] Found', $method);
        static::throwRuntimeException($message);
    }

    protected static function getInstanceName(): string
    {
        return static::CRUDATOR_SERVICE_INSTANCE_NAME;
    }
}
