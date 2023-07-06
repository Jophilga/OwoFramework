<?php

namespace application\libraries\owo\interfaces\Storages;

use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;
use application\libraries\owo\interfaces\Storages\OwoStorageInterface;


interface OwoStorageSessionInterface extends OwoStorageInterface
{
    public function setSession(OwoSessionPrefixInterface $session): self;

    public function getSession(): ?OwoSessionPrefixInterface;
}
