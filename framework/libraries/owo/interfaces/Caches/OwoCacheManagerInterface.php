<?php

namespace framework\libraries\owo\interfaces\Caches;

use framework\libraries\owo\interfaces\Caches\OwoCacheEntityInterface;


interface OwoCacheManagerInterface
{
    public function start(): bool;

    public function finish(string $name, bool $overwrite = true): bool;

    public function digest(string $name, callable $callback, bool $overwrite = true): bool;

    public function write(string $name, string $content, bool $overwrite = true): bool;

    public function add(OwoCacheEntityInterface $cache, bool $overwrite = true): bool;

    public function get(string $name, callable $callback = null): ?OwoCacheEntityInterface;

    public function deleteIfObsolete(string $name): self;

    public function delete(string $name): self;

    public function deleteObsoletes(string $pattern = '.*'): self;

    public function deleteAll(string $pattern = '.*'): self;

    public function listAvailables(string $pattern = '.*'): array;

    public function listObsoletes(string $pattern = '.*'): array;

    public function listAll(string $pattern = '.*'): array;

    public function available(string $name, OwoCacheEntityInterface &$cache = null): bool;

    public function usable(OwoCacheEntityInterface $cache): bool;

    public function setEncrypt(bool $encrypt): self;

    public function getEncrypt(): ?bool;

    public function setDuration(int $duration): self;

    public function getDuration(): ?int;
}
