<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


trait OwoMakeCommonSingletonTrait
{
    use OwoMakeCommonThrowerTrait;

    protected static $instance = null;

    protected static function defaultInstance(): self
    {
        return new static();
    }

    public static function setSingleton(object $instance)
    {
        if (true !== \is_a($instance, static::class, false)) {
            $message = \sprintf('Not Instance Of [%s] Found', static::class);
            static::throwRuntimeException($message);
        }

        if (true === \is_null(static::$instance)) {
            static::$instance = $instance;
        }
    }

    public static function getSingleton(): self
    {
        if (true === \is_null(static::$instance)) {
            static::setSingleton(static::defaultInstance());
        }
        return static::$instance;
    }
}
