<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeCookiesTrait
{
    protected $cookies = [];

    public function __construct(array $cookies = [])
    {
        $this->setCookies($cookies);
    }

    public function setCookies(array $cookies): self
    {
        return $this->emptyCookies()->addCookies($cookies);
    }

    public function emptyCookies(): self
    {
        $this->cookies = [];
        return $this;
    }

    public function addCookies(array $cookies): self
    {
        foreach ($cookies as $key => $value) $this->addCookie($key, $value);
        return $this;
    }

    public function addCookie($key, $value): self
    {
        $this->cookies[$key] = $value;
        return $this;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function hasCookie($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->cookies, $key));
    }

    public function removeCookie($key): self
    {
        unset($this->cookies[$key]);
        return $this;
    }

    public function getCookie($key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }
}
