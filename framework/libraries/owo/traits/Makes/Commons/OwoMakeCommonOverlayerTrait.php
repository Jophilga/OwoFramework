<?php

namespace framework\libraries\owo\traits\Makes\Commons;


trait OwoMakeCommonOverlayerTrait
{
    public static function __callStatic(string $name, array $arguments)
    {
        return static::callAsAccessory($name, $arguments);
    }

    public static function callAsAccessory(string $name, array $arguments)
    {
        if (true !== \is_null($instance = static::getAccessoryInstance())) {
            if (true === \method_exists($instance, $name)) {
                return \call_user_func_array([$instance, $name], $arguments);
            }
        }

        $message = \sprintf('Static Method [%s] Not Resolvable', $name);
        static::throwRuntimeException($message);
    }

    abstract public static function setAccessoryInstance(object $instance);

    abstract public static function getAccessoryInstance(): ?object;
}
