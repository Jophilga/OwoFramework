<?php

namespace framework\libraries\owo\classes\Sessions;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Sessions\OwoSessionInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeOptionsTrait;


class OwoSession implements OwoSessionInterface
{
    use OwoTakeArrayKeyMixeOptionsTrait;

    use OwoMakeCommonThrowerTrait;

    public const SESSION_DEFAULT_OPTIONS = [
        'use_only_cookies' => 1,
        'cookie_lifetime' => 0,
        'cookie_secure' => 1,
        'cookie_httponly' => 1,
    ];

    public function __construct(array $options = [])
    {
        $this->setOptions(static::SESSION_DEFAULT_OPTIONS);
        $this->addOptions($options);
    }

    public function save($key, $value): self
    {
        $this->ensureStartedSession();
        $_SESSION[$key] = $value;
        return $this;
    }

    public function get($key, $default = null)
    {
        if (true === $this->has($key)) return $_SESSION[$key];
        return $default;
    }

    public function removeAll(): self
    {
        foreach ($this->all() as $key => $value) {
            $this->remove($key);
        }
        return $this;
    }

    public function remove($key): self
    {
        $this->ensureStartedSession();
        unset($_SESSION[$key]);
        return $this;
    }

    public function has($key): bool
    {
        $this->ensureStartedSession();
        return (true === OwoHelperArray::hasSetKey($_SESSION, $key));
    }

    public function all(): array
    {
        $this->ensureStartedSession();
        return $_SESSION ?? [];
    }

    public function rename(string $name): bool
    {
        if (true !== $this->isActive()) {
            return (false !== \session_name($name));
        }
        return false;
    }

    public function name(): string
    {
        return \session_name();
    }

    public function start(): bool
    {
        if (true !== $this->isActive()) {
            return (true === \session_start($this->options));
        }
        return true;
    }

    public function destroy(): bool
    {
        if ((true === $this->isActive()) && (true === $this->unset())) {
            $this->deleteCookie();
            return (true === \session_destroy());
        }
        return false;
    }

    public function isActive(): bool
    {
        return (\PHP_SESSION_ACTIVE === $this->status());
    }

    public function unset(): bool
    {
        return (true === \session_unset());
    }

    public function status(): int
    {
        return \session_status();
    }

    public function setCookieParams(array $params): self
    {
        if (true !== $this->isActive()) {
            \session_set_cookie_params($params);
        }
        return $this;
    }

    public function getCookieParams(): array
    {
        if (true === $this->usesCookies()) {
            return \session_get_cookie_params();
        }
        return [];
    }

    public function usesCookies(): bool
    {
        return (1 === \intval(\ini_get('session.use_cookies')));
    }

    protected function ensureStartedSession(): self
    {
        if ((true !== $this->isActive()) && (true !== $this->start())) {
            static::throwRuntimeException('Session Start Failed');
        }
        return $this;
    }
}
