<?php

namespace framework\libraries\owo\classes\Sessions;

use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;
use framework\libraries\owo\interfaces\Sessions\OwoSesisionCsrfInterface;


class OwoSesisionCsrf implements OwoSesisionCsrfInterface
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

    public function getNewCrsfToken(int $length = 30): string
    {
        $this->addCrsfToken($csrf_token = OwoHelperString::random($length));
        return $crsf_token;
    }

    public function useCrsfToken(string $crsf_token): bool
    {
        if (true === $this->hasCrsfToken($crsf_token)) {
            $this->removeCrsfToken($crsf_token);
            return true;
        }
        return false;
    }

    public function hasCrsfToken(string $crsf_token): bool
    {
        return (true === \in_array($crsf_token, $this->getCrsfTokens(), true));
    }

    public function deleteCrsfToken(string $crsf_token): self
    {
        foreach ($this->getCrsfTokens() as $key => $value) {
            if ($crsf_token === $value) $this->session->remove($key);
        }
        return $this;
    }

    public function deleteCrsfTokens(): self
    {
        foreach ($this->getCrsfTokens() as $key => $value) {
            $this->session->remove($key);
        }
        return $this;
    }

    public function getCrsfTokens(): array
    {
        $search = \sprintf('%s.csrf.', $this->session->getPrefix());
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    protected function addCrsfToken(string $crsf_token, int &$index = null): self
    {
        $index = \count($this->getCrsfTokens());
        $this->session->save($this->prepareCrsfTokenKey($index), $crsf_token);
        return $this;
    }

    protected function prepareCrsfTokenKey($key): string
    {
        return \sprintf('crsf.%s', \strval($key));
    }
}
