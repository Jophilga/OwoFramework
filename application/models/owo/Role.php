<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoRoleRepository;


class Role extends OwoCoreModel
{
    protected $name = null;
    protected $scope = null;
    protected $created_at = null;
    protected $updated_at = null;

    public const ROLE_ADMIN = 'ADMIN';

    public const ROLE_USER = 'USER';

    public const ROLE_GUEST = 'GUEST';

    public const ROLE_HIDDENS = [];

    public function isRoleAdmin(): bool
    {
        return (static::ROLE_ADMIN === $this->name);
    }

    public function isRoleUser(): bool
    {
        return (static::ROLE_USER === $this->name);
    }

    public function isRoleGuest(): bool
    {
        return (static::ROLE_GUEST === $this->name);
    }

    public static function getRepositoryClassName(): string
    {
        return OwoRoleRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::ROLE_HIDDENS;
    }
}
