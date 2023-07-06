<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreValidator;
use application\models\owo\Role;


class OwoRoleValidator extends OwoCoreValidator
{
    public const ROLE_VALIDATOR_VALIDATIONS = [
        'name' => 'require | min(1)',
        'created_at' => 'require | datetime(Y-m-d H:i:s)',
        'updated_at' => 'require | datetime(Y-m-d H:i:s)',
        'id' => 'require | integer',
    ];

    protected static function parseEntityToArray(object $entity): array
    {
        return static::ensureSuitableEntity($entity)->toArray();
    }

    public static function getValidations(): array
    {
        return static::ROLE_VALIDATOR_VALIDATIONS;
    }

    public static function getEntityClassName(): string
    {
        return Role::class;
    }
}
