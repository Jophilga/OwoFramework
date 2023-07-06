<?php

namespace framework\libraries\owo\classes\Stackers;

use framework\libraries\owo\classes\Stackers\OwoStacker;

use framework\libraries\owo\interfaces\Stackers\OwoStackerOnionInterface;


class OwoStackerOnion extends OwoStacker implements OwoStackerOnionInterface
{
    public function __construct(array $stackables = [])
    {
        $this->setStackables($stackables);
    }

    public function __invoke(object $bundle, $default = null)
    {
        return $this->process($bundle, $default);
    }

    public function process(object $bundle, $default = null)
    {
        return \call_user_func($this->getStreamer($default), $bundle);
    }

    public function getStreamer($default = null): callable
    {
        $streamer = static::resolver($default);
        foreach (\array_reverse($this->stackables) as $stackable) {
            $streamer = static::next($stackable, $streamer);
        }
        return $streamer;
    }

    public function addStackable($stackable): self
    {
        if (true !== static::isProperStackable($stackable)) {
            static::throwRuntimeException('Inappropriate Stackable Found');
        }
        \array_push($this->stackables, $stackable);
        return $this;
    }

    public static function resolver($default = null): callable
    {
        return function ($bundle) use ($default) {
            if (true === static::isProperStackable($default)) {
                return \call_user_func(static::next($default), $bundle);
            }
            return $default;
        };
    }

    public static function next(callable $callback, callable $next = null): callable
    {
        return function ($bundle) use ($callback, $next) {
            $next = $next ?? function ($bundle) { return null; };
            return \call_user_func($callback, $bundle, $next);
        };
    }

    public static function ensureOnionHandler($handler): callable
    {
        return function (object $bundle, $default = null) use ($handler) {
            return static::ensureOnion($handler)->process($bundle, $default);
        };
    }

    public static function ensureOnion($onion): OwoStackerOnionInterface
    {
        if (true === \is_subclass_of($onion, OwoStackerOnionInterface::class)) {
            return $onion;
        }
        elseif (true === \is_array($onion)) return new static($onion);
        elseif ((true === \is_string($onion)) && (true === \class_exists($onion))) {
            return new static([ new $onion() ]);
        }
        return new static([ $onion ]);
    }

    public static function isProperStackable($stackable): bool
    {
        return (true === \is_callable($stackable, false));
    }
}
