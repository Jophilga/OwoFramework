<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreValidator;
use application\models\owo\Token;


class OwoTokenValidator extends OwoCoreValidator
{
    public const TOKEN_VALIDATOR_VALIDATIONS = [
        'token' => 'require | min(1)',
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
        return static::TOKEN_VALIDATOR_VALIDATIONS;
    }

    public static function getEntityClassName(): string
    {
        return Token::class;
    }
}
