<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyCallablePermissionsTrait
{
    protected $permissions = [];

    public function __construct(array $permissions = [])
    {
        $this->setPermissions($permissions);
    }

    public function setPermissions(array $permissions): self
    {
        return $this->emptyPermissions()->addPermissions($permissions);
    }

    public function emptyPermissions(): self
    {
        $this->permissions = [];
        return $this;
    }

    public function addPermissions(array $permissions): self
    {
        foreach ($permissions as $key => $value) $this->addPermission($key, $value);
        return $this;
    }

    public function addPermission($key, callable $value): self
    {
        $this->permissions[$key] = $value;
        return $this;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function hasPermission($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->permissions, $key));
    }

    public function removePermission($key): self
    {
        unset($this->permissions[$key]);
        return $this;
    }

    public function getPermission($key, $default = null): ?callable
    {
        return $this->permissions[$key] ?? $default;
    }
}
