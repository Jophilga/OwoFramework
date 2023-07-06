<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


abstract class OwoCoreMiddleware implements OwoCoreMiddlewareInterface
{
    public static function isMiddlewarable($middleware): bool
    {
        if (true === \is_callable($middleware)) {
            if (true === \is_subclass_of($middleware, OwoCoreMiddlewareInterface::class)) {
                return true;
            }

            $params = static::getCallableReflection($middleware)->getParameters();
            return (true === static::suitsMiddlewareParams($params));
        }
        return false;
    }

    public static function suitsMiddlewareParams(array $params): bool
    {
        if (true === empty($params)) return true;
        elseif (true === static::isServerRequestParam($params[0])) {
            if (true === OwoHelperArray::hasSetKey($params, 1)) {
                return (true === static::isCallableParam($params[1]));
            }
            return true;
        }
        return false;
    }

    public static function isCallableParam(\ReflectionParameter $param): bool
    {
        if (true === \is_null($type = $param->getType())) {
            return true;
        }

        if (true === \is_a($type, \ReflectionNamedType::class)) {
            return (true === \in_array($type->getName(), ['callable', 'mixed'], true));
        }
        return false;
    }

    public static function isServerRequestParam(\ReflectionParameter $param): bool
    {
        if (true === \is_null($type = $param->getType())) {
            return true;
        }

        if (true === \is_a($type, \ReflectionNamedType::class)) {
            $names = [ OwoServerRequestInterface::class, 'mixed', ];
            return (true === \in_array($type->getName(), $names, true));
        }
        return false;
    }

    public static function getCallableReflection(callable $callable): \ReflectionFunctionAbstract
    {
        if (true === \is_object($callable)) {
            return new \ReflectionMethod($callable, '__invoke');
        }
        elseif ((true === \is_string($callable)) && (false !== \strpos($callable, '::'))) {
            return new \ReflectionMethod($callable);
        }
        elseif (true === \is_array($callable)) {
            return new \ReflectionMethod($callable[0], $callable[1]);
        }
        return new \ReflectionFunction($callable);
    }
}
