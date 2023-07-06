<?php

namespace framework\libraries\owo\interfaces\Events;

use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;


interface OwoEventManagerInterface
{
    public function setObservers(array $observers): self;

    public function emptyObservers(): self;

    public function mapObservers(array $observers): self;

    public function mapObserver(string $event, $observer): self;

    public function addObserver(string $event, OwoEventObserverInterface $observer): self;

    public function getObservers(string $event = null): array;

    public function getObserver(string $event, string $name, $default = null): ?OwoEventObserverInterface;

    public function hasObserver(string $event, string $name = null): bool;

    public function removeObservers(string $event): self;

    public function removeObserver(string $event, string $name): self;
}
