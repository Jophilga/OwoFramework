<?php

namespace application\repositories\owo;

use framework\libraries\owo\classes\Cores\OwoCoreValidator;
use application\models\owo\Provider;


class OwoProviderValidator extends OwoCoreValidator
{
    public const PROVIDER_VALIDATOR_VALIDATIONS = [
        'authorization_endpoint' => 'require | min(1)',
        'token_endpoint' => 'require | min(1)',
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
        return static::PROVIDER_VALIDATOR_VALIDATIONS;
    }

    public static function getEntityClassName(): string
    {
        return Provider::class;
    }
}
