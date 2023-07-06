<?php

namespace framework\libraries\owo\traits\Takes\Integers;


trait OwoTakeIntegerDurationTrait
{
    protected $duration = null;

    public function __construct(int $duration)
    {
        $this->setDuration($duration);
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }
}
