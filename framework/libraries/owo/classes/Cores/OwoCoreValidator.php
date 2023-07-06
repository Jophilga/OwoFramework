<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\classes\Casters\OwoCasterInstantiator;
use framework\libraries\owo\classes\Inputs\OwoInputValidator;

use framework\libraries\owo\interfaces\Inputs\OwoInputValidatorInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreValidatorInterface;


abstract class OwoCoreValidator implements OwoCoreValidatorInterface
{
    protected static function ensureSuitableEntity(object $entity): object
    {
        $classname = static::getEntityClassName();
        if (true !== OwoCasterInstantiator::isSubOrInstanceOf($entity, $classname)) {
            static::throwRuntimeException('Not Suitable Entity Found');
        }
        return $entity;
    }

    public static function valideInputs(array $inputs): OwoInputValidatorInterface
    {
        return OwoInputValidator::realize($inputs, static::getValidations());
    }

    public static function validateEntity(object $entity): OwoInputValidatorInterface
    {
        return static::valideInputs(static::parseEntityToArray($entity));
    }

    abstract protected static function parseEntityToArray(object $entity): array;

    abstract public static function getValidations(): array;

    abstract public static function getEntityClassName(): string;
}
