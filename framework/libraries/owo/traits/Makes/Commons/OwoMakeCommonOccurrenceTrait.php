<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\interfaces\Events\OwoEventDispatcherInterface;
use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


trait OwoMakeCommonOccurrenceTrait
{
    use OwoMakeCommonThrowerTrait;

    protected static $dispatcher = null;

    protected static function ensureDispatcher(): OwoEventDispatcherInterface
    {
        if (true === \is_null($dispatcher = static::getDispatcher())) {
            static::throwRuntimeException('Static Event Dispatcher Instance Not Found');
        }
        return $dispatcher;
    }

    public static function triggerObservers(array $observers, array $args = []): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->triggerObservers($observers, $args);
    }

    public static function triggerObserver(OwoEventObserverInterface $observer, array $args = []): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->triggerObserver($observer, $args);
    }

    public static function emitAllGlobally(array $excludes = [], array $args = []): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->emitAllGlobally($excludes, $args);
    }

    public static function emitAll(array $events, array $args = []): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->emitAll($events, $args);
    }

    public static function emit(string $event, string $name, array $args = []): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->emit($event, $name, $args);
    }

    public static function attach(string $event, callable $action, int $prior = 0, string $name = null): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->attach($event, $action, $prior, $name);
    }

    public static function detachAll(string $event): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->detachAll($event);
    }

    public static function detach(string $event, string $name): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->detach($event, $name);
    }

    public static function setObservers(array $observers): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->setObservers($observers);
    }

    public static function emptyObservers(): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->emptyObservers();
    }

    public static function mapObservers(array $observers): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->mapObservers($observers);
    }

    public static function mapObserver(string $event, $observer): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->mapObserver($event, $observer);
    }

    public static function addObserver(string $event, OwoEventObserverInterface $observer): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->addObserver($event, $observer);
    }

    public static function getObservers(string $event = null): array
    {
        return static::ensureDispatcher()->getObservers($event);
    }

    public static function getObserver(string $event, string $name, $default = null): ?OwoEventObserverInterface
    {
        return static::ensureDispatcher()->getObserver($event, $name, $default);
    }

    public static function hasObserver(string $event, string $name = null): bool
    {
        return (true === static::ensureDispatcher()->hasObserver($event, $name));
    }

    public static function removeObservers(string $event): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->removeObservers($event);
    }

    public static function removeObserver(string $event, string $name): OwoEventDispatcherInterface
    {
        return static::ensureDispatcher()->removeObserver($event, $name);
    }

    public static function setDispatcher(OwoEventDispatcherInterface $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    public static function getDispatcher(): ?OwoEventDispatcherInterface
    {
        return static::$dispatcher;
    }
}
