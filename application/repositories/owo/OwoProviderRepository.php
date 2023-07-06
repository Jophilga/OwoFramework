<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\Provider;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoProviderRepository extends OwoCoreRepository
{
    public const PROVIDER_REPOSITORY_TABLE = 'owo_providers';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::PROVIDER_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new Provider())->digest($entry);
    }
}
