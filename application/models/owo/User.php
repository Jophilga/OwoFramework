<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoUserRepository;
use application\models\owo\Role;


class User extends OwoCoreModel
{
    protected $username = null;
    protected $password = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $email = null;
    protected $phone = null;
    protected $scope = null;
    protected $email_token = null;
    protected $email_verified = null;
    protected $email_verified_at = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $role_id = null;

    public const USER_HIDDENS = [];

    public function hasRoleAdmin(): bool
    {
        if (true !== \is_null($role = $this->getRole())) {
            return (true === $role->isRoleAdmin());
        }
        return false;
    }

    public function hasRoleUser(): bool
    {
        if (true !== \is_null($role = $this->getRole())) {
            return (true === $role->isRoleUser());
        }
        return false;
    }

    public function hasRoleGuest(): bool
    {
        if (true !== \is_null($role = $this->getRole())) {
            return (true === $role->isRoleGuest());
        }
        return false;
    }

    public function getRole(): ?Role
    {
        if (true !== \is_null($this->role_id)) {
            return Role::findOne($this->role_id, []);
        }
        return null;
    }

    public static function getRepositoryClassName(): string
    {
        return OwoUserRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::USER_HIDDENS;
    }
}
