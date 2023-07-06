<?php

namespace application\services\owo;

use framework\libraries\owo\classes\Cores\OwoCoreService;
use framework\libraries\owo\classes\Helpers\OwoHelperPassword;
use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use application\services\owo\OwoAuthenticatorService;
use application\models\owo\User;
use application\models\owo\Client;
use application\models\owo\Token;


class OwoRegistrarService extends OwoCoreService
{
    use OwoMakeCommonThrowerTrait;

    public const REGISTRAR_SERVICE_INSTANCE_NAME = 'registrar';

    public function handleUserRegistry(OwoServerRequestInterface $request): ?User
    {
        return $this->registerNewUser($request->getInputs());
    }

    public function handleClientRegistry(OwoServerRequestInterface $request, User $user = null): ?Client
    {
        $user = $user ?? $this->ensureUserAuthenticated($request);
        return $this->registerNewClient($user, $request->getInputs());
    }

    public function handleTokenRegistry(OwoServerRequestInterface $request, User $user = null): ?User
    {
        $user = $user ?? $this->ensureUserAuthenticated($request);
        return $this->registerNewToken($user, $request->getInputs());
    }

    public function registerNewUser(array $inputs, array $options = []): ?User
    {
        if (true === OwoHelperArray::hasSetKey($inputs, 'email')) {
            $inputs['username'] = $inputs['username'] ?? $inputs['email'];
        }

        if (true === OwoHelperArray::hasSetKey($inputs, 'password')) {
            $inputs['password'] = OwoHelperPassword::hash($inputs['password'], $options);
        }

        $inputs['email_token'] = OwoHelperString::random(12);
        return User::createOne($inputs);
    }

    public function registerNewClient(User $user, array $inputs): ?Client
    {
        $inputs['pkey'] = OwoHelperString::randomPassPhrase(5, 4, '-');
        $inputs['secret'] = OwoHelperString::random(20);
        $inputs['user_id'] = $user->getId();
        return Client::createOne($inputs);
    }

    public function registerNewToken(User $user, array $inputs): ?Token
    {
        $inputs['lifetime'] = $inputs['lifetime'] ?? Token::TOKEN_DEFAULT_LIFETIME;
        $inputs['is_revoked'] = false;
        $inputs['user_id'] = $user->getId();
        return Token::createOne($inputs);
    }

    protected function ensureUserAuthenticated(OwoServerRequestInterface $request): User
    {
        if (true === \is_null($user = $this-> getUserAuthenticated($request))) {
            static::throwRuntimeException('User Registering Not Found');
        }
        return $user;
    }

    protected function getUserAuthenticated(OwoServerRequestInterface $request): ?User
    {
        return OwoAuthenticatorService::getInstance()->authenticate($request);
    }

    protected static function getInstanceName(): string
    {
        return static::REGISTRAR_SERVICE_INSTANCE_NAME;
    }
}
