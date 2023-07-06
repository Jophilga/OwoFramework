<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\AccessToken;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoAccessTokenRepository extends OwoCoreRepository
{
    public const ACCESS_TOKEN_REPOSITORY_TABLE = 'owo_access_tokens';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::ACCESS_TOKEN_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new AccessToken())->digest($entry);
    }
}
