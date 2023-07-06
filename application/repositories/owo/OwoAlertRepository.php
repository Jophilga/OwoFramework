<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\Alert;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoAlertRepository extends OwoCoreRepository
{
    public const ALERT_REPOSITORY_TABLE = 'owo_alerts';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::ALERT_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new Alert())->digest($entry);
    }
}
