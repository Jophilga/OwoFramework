<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoProviderRepository;


class Provider extends OwoCoreModel
{
    protected $authorization_endpoint = null;
    protected $token_endpoint = null;
    protected $scope = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $client_public_key = null;
    protected $client_secret = null;
    protected $user_id = null;

    public const PROVIDER_HIDDENS = [];

    public static function getRepositoryClassName(): string
    {
        return OwoProviderRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::PROVIDER_HIDDENS;
    }
}
