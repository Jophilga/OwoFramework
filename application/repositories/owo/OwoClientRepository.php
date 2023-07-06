<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\Client;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoClientRepository extends OwoCoreRepository
{
    public const CLIENT_REPOSITORY_TABLE = 'owo_clients';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::CLIENT_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new Client())->digest($entry);
    }
}
