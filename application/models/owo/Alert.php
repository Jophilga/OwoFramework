<?php

namespace application\models\owo;

use framework\libraries\owo\classes\Cores\OwoCoreModel;
use application\repositories\owo\OwoAlertRepository;


class Alert extends OwoCoreModel
{
    protected $type = null;
    protected $message = null;
    protected $scope = null;
    protected $created_at = null;
    protected $updated_at = null;
    protected $user_id = null;

    public const ALERT_HIDDENS = [];

    public static function getRepositoryClassName(): string
    {
        return OwoArticleRepository::class;
    }

    public static function getHiddens(): array
    {
        return static::ALERT_HIDDENS;
    }
}
