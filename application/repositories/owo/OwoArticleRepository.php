<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreRepository;
use application\models\owo\Article;

use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;


class OwoArticleRepository extends OwoCoreRepository
{
    public const ARTICLE_REPOSITORY_TABLE = 'owo_articles';

    public function __construct(OwoDbaseConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    public static function getTable(): string
    {
        return static::ARTICLE_REPOSITORY_TABLE;
    }

    public static function constructOne(array $entry): object
    {
        return (new Article())->digest($entry);
    }
}
