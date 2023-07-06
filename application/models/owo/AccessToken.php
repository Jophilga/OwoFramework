<?php

namespace application\models\owo;

use application\repositories\owo\OwoAccessTokenRepository;
use framework\libraries\owo\classes\Cores\OwoCoreModel;


class AccessToken extends OwoCoreModel
{
    protected $access_token = null;
    protected $refresh_token = null;
    protected $token_type = null;
    protected $scope = null;
    protected $expires_in = null;
    protected $issued_at = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $provider_id = null;
    protected $client_id = null;
    protected $user_id = null;

    public const ACCESS_TOKEN_HIDDENS = [];

    public static function getRepositoryClassName(): string
    {
        return OwoArticleRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::ACCESS_TOKEN_HIDDENS;
    }
}
