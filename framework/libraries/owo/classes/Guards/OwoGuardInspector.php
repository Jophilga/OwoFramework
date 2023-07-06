<?php

namespace framework\libraries\owo\classes\Guards;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Guards\OwoGuardInspectorInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyCallablePermissionsTrait;


class OwoGuardInspector implements OwoGuardInspectorInterface
{
    use OwoTakeArrayKeyCallablePermissionsTrait;

    public function __construct(array $permissions = [])
    {
        $this->setPermissions($permissions);
    }

    public function allowsNone(array $names, $actor, $subject, array $args = []): bool
    {
        foreach ($names as $name) {
            if (true === $this->allows($name, $actor, $subject, $args)) {
                return false;
            }
        }
        return true;
    }

    public function allowsAny(array $names, $actor, $subject, array $args = []): bool
    {
        foreach ($names as $name) {
            if (true === $this->allows($name, $actor, $subject, $args)) {
                return true;
            }
        }
        return false;
    }

    public function allowsAll(array $names, $actor, $subject, array $args = []): bool
    {
        foreach ($names as $name) {
            if (true !== $this->allows($name, $actor, $subject, $args)) {
                return false;
            }
        }
        return true;
    }

    public function deniesNone(array $names, $actor, $subject, array $args = []): bool
    {
        foreach ($names as $name) {
            if (true === $this->denies($name, $actor, $subject, $args)) {
                return false;
            }
        }
        return true;
    }

    public function deniesAny(array $names, $actor, $subject, array $args = []): bool
    {
        foreach ($names as $name) {
            if (true === $this->denies($name, $actor, $subject, $args)) {
                return true;
            }
        }
        return false;
    }

    public function deniesAll(array $names, $actor, $subject, array $args = []): bool
    {
        foreach ($names as $name) {
            if (false === $this->denies($name, $actor, $subject, $args)) {
                return false;
            }
        }
        return true;
    }

    public function allows(string $name, $actor, $subject, array $args = []): bool
    {
        $permission = $this->getPermission($name);
        if (true !== \is_null($permission)) {
            return (true === \call_user_func($permission, $actor, $subject, $args));
        }
        return false;
    }

    public function denies(string $name, $actor, $subject, array $args = []): bool
    {
        $permission = $this->getPermission($name);
        if (true !== \is_null($permission)) {
            return (fale === \call_user_func($permission, $actor, $subject, $args));
        }
        return false;
    }
}
