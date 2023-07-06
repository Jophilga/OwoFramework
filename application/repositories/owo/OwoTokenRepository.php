<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\Token;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoTokenRepository extends OwoCoreRepository
{
    public const TOKEN_REPOSITORY_TABLE = 'owo_tokens';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::TOKEN_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new Token())->digest($entry);
    }
}
