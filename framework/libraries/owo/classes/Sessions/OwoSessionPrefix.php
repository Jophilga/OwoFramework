<?php

namespace framework\libraries\owo\classes\Sessions;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;
use framework\libraries\owo\interfaces\Sessions\OwoSessionInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPrefixTrait;


class OwoSessionPrefix implements OwoSessionPrefixInterface
{
    use OwoTakeStringPrefixTrait;

    protected $session = null;

    public function __construct(OwoSessionInterface $session, string $prefix)
    {
        $this->setSession($session)->setPrefix($prefix);
    }

    public function setSession(OwoSessionInterface $session): self
    {
        $this->session = $session;
        return $this;
    }

    public function getSession(): ?OwoSessionInterface
    {
        return $this->session;
    }

    public function all(): array
    {
        $search = \sprintf('%s.', \strval($this->prefix));
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    public function save($key, $value): self
    {
        $this->session->save($this->prepareKey($key), $value);
        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->session->get($this->prepareKey($key), $default = null);
    }

    public function removeAll(): self
    {
        foreach ($this->all() as $key => $value) {
            $this->session->remove($key);
        }
        return $this;
    }

    public function remove($key): self
    {
        $this->session->remove($this->prepareKey($key));
        return $this;
    }

    public function has($key): bool
    {
        return (true === $this->session->has($this->prepareKey($key)));
    }

    public function name(): string
    {
        return $this->session->name();
    }

    public function rename(string $name): bool
    {
        return (true === $this->session->rename($name));
    }

    public function start(): bool
    {
        return (true === $this->session->start());
    }

    public function destroy(): bool
    {
        return (true === $this->session->destroy());
    }

    public function isActive(): bool
    {
        return (true === $this->session->isActive());
    }

    public function unset(): bool
    {
        return (true === $this->session->unset());
    }

    public function status(): int
    {
        return $this->session->status();
    }

    public function setCookieParams(array $params): self
    {
        $this->session->setCookieParams($params);
        return $this;
    }

    public function getCookieParams(): array
    {
        return $this->session->getCookieParams();
    }

    public function usesCookies(): bool
    {
        return (true === $this->session->usesCookies());
    }

    protected function prepareKey($key): string
    {
        return \sprintf('%s.%s', \strval($this->prefix), \strval($key));
    }
}
