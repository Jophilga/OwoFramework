<?php

namespace framework\libraries\owo\interfaces\Sessions;


interface OwoSessionInterface
{
    public function all(): array;

    public function save($key, $value): self;

    public function get($key, $default = null);

    public function removeAll(): self;

    public function remove($key): self;

    public function has($key): bool;

    public function name(): string;

    public function rename(string $name): bool;

    public function start(): bool;

    public function destroy(): bool;

    public function isActive(): bool;

    public function unset(): bool;

    public function status(): int;

    public function setCookieParams(array $params): self;

    public function getCookieParams(): array;

    public function usesCookies(): bool;
}
