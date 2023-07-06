<?php

namespace framework\libraries\owo\interfaces\Sessions;

use framework\libraries\owo\interfaces\Sessions\OwoSessionInterface;


interface OwoSessionImitorInterface extends OwoSessionInterface
{
    public function setSession(OwoSessionInterface $session): self;

    public function getSession(): ?OwoSessionInterface;
}
