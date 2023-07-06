<?php

namespace framework\libraries\owo\classes\Caches;

use framework\libraries\owo\classes\Caches\OwoCacheManager;

use framework\libraries\owo\interfaces\Caches\OwoCacheSessionInterface;
use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;
use framework\libraries\owo\interfaces\Caches\OwoCacheEntityInterface;


class OwoCacheSession extends OwoCacheManager implements OwoCacheSessionInterface
{
    protected $session = null;

    public function __construct(OwoSessionPrefixInterface $session, int $duration = 3600, bool $encrypt = true)
    {
        parent::__construct($duration, $encrypt);
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

    protected function getCaches(): array
    {
        return $this->session->all();
    }

    protected function saveCache(string $name, OwoCacheEntityInterface $cache): self
    {
        $this->session->save($name, $cache);
        return $this;
    }

    protected function getCache(string $name, $default = null): ?OwoCacheEntityInterface
    {
        return $this->session->get($name, $default);
    }

    protected function removeCache(string $name): self
    {
        $this->session->remove($name);
        return $this;
    }
}
