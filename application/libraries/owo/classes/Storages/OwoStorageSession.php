<?php

namespace application\libraries\owo\classes\Storages;

use application\libraries\owo\interfaces\Storages\OwoStorageSessionInterface;
use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;


class OwoStorageSession implements OwoStorageSessionInterface
{
    protected $session = null;

    public function __construct(OwoSessionPrefixInterface $session)
    {
        $this->setSession($session);
    }

    public function setSession(OwoSessionPrefixInterface $session): self
    {
        $this->session = $session;
        return $this;
    }

    public function getSession(): ?OwoSessionPrefixInterface
    {
        return $this->session;
    }
}
