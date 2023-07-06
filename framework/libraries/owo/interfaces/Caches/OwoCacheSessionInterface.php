<?php

namespace framework\libraries\owo\interfaces\Caches;

use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;
use framework\libraries\owo\interfaces\Caches\OwoCacheManagerInterface;


interface OwoCacheSessionInterface extends OwoCacheManagerInterface
{
    public function setSession(OwoSessionPrefixInterface $session): self;

    public function getSession(): ?OwoSessionPrefixInterface;
}
