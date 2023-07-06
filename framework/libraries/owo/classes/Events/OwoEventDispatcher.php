<?php

namespace framework\libraries\owo\classes\Events;

use framework\libraries\owo\classes\Events\OwoEventObserver;
use framework\libraries\owo\classes\Events\OwoEventManager;

use framework\libraries\owo\interfaces\Events\OwoEventDispatcherInterface;
use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;


class OwoEventDispatcher extends OwoEventManager implements OwoEventDispatcherInterface
{
    public function __construct(array $observers = [])
    {
        parent::__construct($observers);
    }

    public function triggerObservers(array $observers, array $args = []): self
    {
        foreach ($this->sortObservers($observers) as $observer) {
            $this->triggerObserver($observer, $args);
        }
        return $this;
    }

    public function triggerObserver(OwoEventObserverInterface $observer, array $args = []): self
    {
        \call_user_func($observer->getAction(), $this, $args);
        return $this;
    }

    public function emitAllGlobally(array $excludes = [], array $args = []): self
    {
        foreach ($this->getObservers() as $event => $observers) {
            if (true !== \in_array($event, $excludes, true)) {
                $this->triggerObserver($observers, $args);
            }
        }
        return $this;
    }

    public function emitAll(array $events, array $args = []): self
    {
        foreach ($events as $event) {
            $this->triggerObservers($this->getObservers($event), $args);
        }
        return $this;
    }

    public function emit(string $event, string $name, array $args = []): self
    {
        $observer = $this->getObserver($event, $name);
        if (true !== \is_null($observer)) $this->triggerObserver($observer, $args);
        return $this;
    }

    public function attach(string $event, callable $action, int $prior = 0, string $name = null): self
    {
        $observer = new OwoEventObserver($event, $action, $prior);
        if (true !== \is_null($name)) {
             return $this->addObserver($event, $observer->setName($name));
        }
        return $this->addObserver($event, $observer);
    }

    public function detachAll(string $event): self
    {
        $this->observers[$event] = [];
        return $this;
    }

    public function detach(string $event, string $name): self
    {
        $this->prepareObservers($event);
        unset($this->observers[$event][$name]);
        return $this;
    }
}
