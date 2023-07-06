<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoClientRepository;


class Client extends OwoCoreModel
{
    protected $pkey = null;
    protected $name = null;
    protected $secret = null;
    protected $origin_uris = null;
    protected $redirect_uris = null;
    protected $grant_types = null;
    protected $scope = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $user_id = null;

    public const CLIENT_HIDDENS = [];

    public static function getRepositoryClassName(): string
    {
        return OwoClientRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::CLIENT_HIDDENS;
    }
}
