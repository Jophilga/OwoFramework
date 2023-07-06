<?php

namespace framework\libraries\owo\classes\Events;

use framework\libraries\owo\classes\Events\OwoEventObserver;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;
use framework\libraries\owo\interfaces\Events\OwoEventManagerInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoEventManager implements OwoEventManagerInterface
{
    use OwoMakeCommonThrowerTrait;

    protected $observers = [];

    public function __construct(array $observers = [])
    {
        $this->mapObservers($observers);
    }

    public function setObservers(array $observers): self
    {
        return $this->emptyObservers()->mapObservers($observers);
    }

    public function emptyObservers(): self
    {
        $this->observers = [];
        return $this;
    }

    public function mapObservers(array $observers): self
    {
        foreach ($observers as $event => $observer) $this->mapObserver($event, $observer);
        return $this;
    }

    public function mapObserver(string $event, $observer): self
    {
        if (true === \is_callable($observer)) {
            $this->addObserver($event, new OwoEventObserver($event, $observer));
        }
        elseif (true === \is_array($observer)) {
            foreach ($observer as $name => $action) {
                $observer = new OwoEventObserver($event, $action);
                if (true !== \is_null($name)) $observer->setName($name);
                $this->addObserver($event, $observer);
            }
        }
        else $this->addObserver($event, $observer);
        return $this;
    }

    public function addObserver(string $event, OwoEventObserverInterface $observer): self
    {
        $this->prepareObservers($event);
        if (true === \is_null($name = $observer->getName())) {
            $this->observers[$event][] = $observer;
        }
        else $this->observers[$event][$name] = $observer;
        return $this;
    }

    public function getObservers(string $event = null): array
    {
        $this->prepareObservers($event);
        if (true !== \is_null($event)) return $this->observers[$event] ?? [];
        return $this->observers;
    }

    public function getObserver(string $event, string $name, $default = null): ?OwoEventObserverInterface
    {
        $this->prepareObservers($event);
        $observer = $this->observers[$event][$name] ?? $default;
        return $observer;
    }

    public function hasObserver(string $event, string $name = null): bool
    {
        $this->prepareObservers($event);
        if (true !== \is_null($name)) {
            return (true === OwoHelperArray::hasSetKey($this->observers[$event], $name));
        }
        return (true !== empty($this->observers[$event]));
    }

    public function removeObservers(string $event): self
    {
        $this->observers[$event] = [];
        return $this;
    }

    public function removeObserver(string $event, string $name): self
    {
        $this->prepareObservers($event);
        unset($this->observers[$event][$name]);
        return $this;
    }

    protected function prepareObservers(string $event): self
    {
        if (true !== \array_key_exists($event, $this->observers)) {
            $this->observers[$event] = [];
        }
        return $this;
    }

    public static function sortObservers(array $observers): array
    {
        \usort($observers, function ($current, $next) {
            return ($next->getPrior() - $current->getPrior());
        });
        return $observers;
    }
}
