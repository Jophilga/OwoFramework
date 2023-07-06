<?php

namespace framework\libraries\owo\interfaces\Guards;


interface OwoGuardInspectorInterface
{
    public function allows(string $name, $actor, $subject, array $args = []): bool;

    public function allowsNone(array $names, $actor, $subject, array $args = []): bool;

    public function allowsAny(array $names, $actor, $subject, array $args = []): bool;

    public function allowsAll(array $names, $actor, $subject, array $args = []): bool;

    public function deniesNone(array $names, $actor, $subject, array $args = []): bool;

    public function deniesAny(array $names, $actor, $subject, array $args = []): bool;

    public function deniesAll(array $names, $actor, $subject, array $args = []): bool;

    public function denies(string $name, $actor, $subject, array $args = []): bool;

    public function setPermissions(array $permissions): self;

    public function emptyPermissions(): self;

    public function addPermissions(array $permissions): self;

    public function addPermission($key, callable $value): self;

    public function getPermissions(): array;

    public function hasPermission($key): bool;

    public function removePermission($key): self;

    public function getPermission($key, $default = null): ?callable;
}
