<?php

namespace framework\libraries\owo\traits\Makes\Commons;


trait OwoMakeCommonThrowerTrait
{
    protected static function throwRuntimeException(string $message)
    {
        throw new \RuntimeException(static::getExceptionMessage($message));
    }

    protected static function throwInvalidArgumentException(string $message)
    {
        throw new \InvalidArgumentException(static::getExceptionMessage($message));
    }

    protected static function throwUnexpectedValueException(string $message)
    {
        throw new \UnexpectedValueException(static::getExceptionMessage($message));
    }

    protected static function throwLengthException(string $message)
    {
        throw new \LengthException(static::getExceptionMessage($message));
    }

    protected static function throwDomainException(string $message)
    {
        throw new \DomainException(static::getExceptionMessage($message));
    }

    protected static function throwException(string $message)
    {
        throw new \Exception(static::getExceptionMessage($message));
    }

    protected static function getExceptionMessage(string $message): string
    {
        return \sprintf('[%s] : %s', static::class, $message);
    }
}
