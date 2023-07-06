<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreValidator;
use application\models\owo\User;


class OwoUserValidator extends OwoCoreValidator
{
    public const USER_VALIDATOR_VALIDATIONS = [
        'firstname' => 'require | min(1)',
        'lastname' => 'require | min(1)',
        'email' => 'require | min(1)',
        'password' => 'require | min(1)',
        'created_at' => 'require | datetime(Y-m-d H:i:s)',
        'updated_at' => 'require | datetime(Y-m-d H:i:s)',
        'id' => 'require | integer',
        'username' => 'require | min(1)',
    ];

    protected static function parseEntityToArray(object $entity): array
    {
        return static::ensureSuitableEntity($entity)->toArray();
    }

    public static function getValidations(): array
    {
        return static::USER_VALIDATOR_VALIDATIONS;
    }

    public static function getEntityClassName(): string
    {
        return User::class;
    }
}
