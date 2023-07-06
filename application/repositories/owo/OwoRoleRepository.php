<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\Role;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoRoleRepository extends OwoCoreRepository
{
    public const ROLE_REPOSITORY_TABLE = 'owo_roles';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::ROLE_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new Role())->digest($entry);
    }
}
