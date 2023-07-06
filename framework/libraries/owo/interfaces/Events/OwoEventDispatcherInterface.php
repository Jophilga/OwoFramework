<?php

namespace framework\libraries\owo\interfaces\Events;

use framework\libraries\owo\interfaces\Events\OwoEventManagerInterface;
use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;


interface OwoEventDispatcherInterface extends OwoEventManagerInterface
{
    public function triggerObservers(array $observers, array $args = []): self;

    public function triggerObserver(OwoEventObserverInterface $observer, array $args = []): self;

    public function emitAllGlobally(array $excludes = [], array $args = []): self;

    public function emitAll(array $events, array $args = []): self;

    public function emit(string $event, string $name, array $args = []): self;

    public function attach(string $event, callable $action, int $prior = 0, string $name = null): self;

    public function detachAll(string $event): self;

    public function detach(string $event, string $name): self;
}
