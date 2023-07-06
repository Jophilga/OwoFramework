<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoArticleRepository;


class Article extends OwoCoreModel
{
    protected $title = null;
    protected $content = null;
    protected $scope = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $user_id = null;

    public const ARTICLE_HIDDENS = [];

    public static function getRepositoryClassName(): string
    {
        return OwoArticleRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::ARTICLE_HIDDENS;
    }
}
