<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreValidator;
use application\models\owo\Article;


class OwoArticleValidator extends OwoCoreValidator
{
    public const ARTICLE_VALIDATOR_VALIDATIONS = [
        'content' => 'require | min(1)',
        'title' => 'require | min(1)',
        'created_at' => 'require | datetime(Y-m-d H:i:s)',
        'updated_at' => 'require | datetime(Y-m-d H:i:s)',
        'id' => 'require | integer',
        'user_id' => 'require | integer',
    ];

    protected static function parseEntityToArray(object $entity): array
    {
        return static::ensureSuitableEntity($entity)->toArray();
    }

    public static function getValidations(): array
    {
        return static::ARTICLE_VALIDATOR_VALIDATIONS;
    }

    public static function getEntityClassName(): string
    {
        return Article::class;
    }
}
