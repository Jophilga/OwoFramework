<?php

namespace application\libraries\owo\interfaces\Storages;

use application\models\owo\User;

use application\libraries\owo\interfaces\Storages\OwoStorageSessionInterface;


interface OwoStorageSesisionUserInterface extends OwoStorageSessionInterface
{
    public function getLastAuthUser($default = null): ?User;

    public function addAuthUser(User $user): self;

    public function deleteAuthUsers(): self;

    public function deleteAuthUser($id): self;

    public function hasAuthUser($id = null): bool;

    public function getAuthUser($id, $default = null): ?User;

    public function getAuthUsers(): array;
}
