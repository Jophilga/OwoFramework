<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


trait OwoMakeCommonUtilityTrait
{
    use OwoMakeCommonThrowerTrait;

    public static function __callStatic(string $name, array $arguments)
    {
        return static::callAsAdditional($name, $arguments);
    }

    public static function callAsAdditional(string $name, array $arguments)
    {
        $methods = static::getAdditionalMethods();
        if (true !== \array_key_exists($name, $methods)) {
            static::throwRuntimeException(\sprintf('Method [%s] Not Found', $name));
        }
        return \call_user_func_array($methods[$name], $arguments);
    }

    abstract protected static function getAdditionalMethods(): array;
}
