<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoTokenRepository;


class Token extends OwoCoreModel
{
    protected $token = null;
    protected $lifetime = null;
    protected $claimer = null;
    protected $scope = null;
    protected $is_revoked = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $user_id = null;

    public const TOKEN_DEFAULT_LIFETIME = 86400;

    public const TOKEN_HIDDENS = [];

    public function hasBeenRevoked(): bool
    {
        return \boolval($this->get('is_revoked', 0));
    }

    public function hasExpiredNow(): bool
    {
        return (true === $this->hasExpiredAt(\time()));
    }

    public function hasExpiredAt(int $time): bool
    {
        $lifetime = \intval($this->get('lifetime', 0));
        $expiration = $lifetime + \strtotime($this->get('created_at'));
        return ($expiration < $time);
    }

    public static function getRepositoryClassName(): string
    {
        return OwoTokenRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::TOKEN_HIDDENS;
    }
}
