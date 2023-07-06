<?php

namespace framework\libraries\owo\interfaces\Events;


interface OwoEventObserverInterface
{
    public function setEvent(string $event): self;

    public function getEvent(): ?string;

    public function setAction(callable $action): self;

    public function getAction(): ?callable;

    public function setPrior(int $prior): self;

    public function getPrior(): ?int;

    public function setName(string $name): self;

    public function getName(): ?string;
}
