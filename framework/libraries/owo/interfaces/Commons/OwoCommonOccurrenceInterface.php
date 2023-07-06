<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Events\OwoEventDispatcherInterface;
use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;


interface OwoCommonOccurrenceInterface
{
    public static function triggerObservers(array $observers, array $args = []): OwoEventDispatcherInterface;

    public static function triggerObserver(OwoEventObserverInterface $observer, array $args = []): OwoEventDispatcherInterface;

    public static function emitAllGlobally(array $excludes = [], array $args = []): OwoEventDispatcherInterface;

    public static function emitAll(array $events, array $args = []): OwoEventDispatcherInterface;

    public static function emit(string $event, string $name, array $args = []): OwoEventDispatcherInterface;

    public static function attach(string $event, callable $action, int $prior = 0, string $name = null): OwoEventDispatcherInterface;

    public static function detachAll(string $event): OwoEventDispatcherInterface;

    public static function detach(string $event, string $name): OwoEventDispatcherInterface;

    public static function setObservers(array $observers): OwoEventDispatcherInterface;

    public static function emptyObservers(): OwoEventDispatcherInterface;

    public static function mapObservers(array $observers): OwoEventDispatcherInterface;

    public static function mapObserver(string $event, $observer): OwoEventDispatcherInterface;

    public static function addObserver(string $event, OwoEventObserverInterface $observer): OwoEventDispatcherInterface;

    public static function getObservers(string $event = null): array;

    public static function getObserver(string $event, string $name, $default = null): ?OwoEventObserverInterface;

    public static function hasObserver(string $event, string $name = null): bool;

    public static function removeObservers(string $event): OwoEventDispatcherInterface;

    public static function removeObserver(string $event, string $name): OwoEventDispatcherInterface;

    public static function setDispatcher(OwoEventDispatcherInterface $dispatcher);

    public static function getDispatcher(): ?OwoEventDispatcherInterface;
}
