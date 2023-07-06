<?php

namespace framework\libraries\owo\interfaces\Cores;

use framework\libraries\owo\interfaces\Inputs\OwoInputValidatorInterface;


interface OwoCoreValidatorInterface
{
    public static function valideInputs(array $inputs): OwoInputValidatorInterface;

    public static function validateEntity(object $entity): OwoInputValidatorInterface;

    public static function getValidations(): array;

    public static function getEntityClassName(): string;
}
