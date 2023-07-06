<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringEventTrait
{
    protected $event = null;

    public function __construct(string $event)
    {
        $this->setEvent($event);
    }

    public function setEvent(string $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }
}
