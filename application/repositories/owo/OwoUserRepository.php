<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\User;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoUserRepository extends OwoCoreRepository
{
    public const USER_REPOSITORY_TABLE = 'owo_users';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::USER_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new User())->digest($entry);
    }
}
