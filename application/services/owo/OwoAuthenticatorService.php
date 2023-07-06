<?php

namespace application\services\owo;

use framework\libraries\owo\classes\Helpers\OwoHelperDecoder;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelperPassword;
use framework\libraries\owo\classes\Cores\OwoCoreService;

use application\services\owo\OwoRegistrarService;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use application\models\owo\User;
use application\models\owo\Token;


class OwoAuthenticatorService extends OwoCoreService
{
    use OwoMakeCommonThrowerTrait;

    public const AUTHENTICATOR_SERVICE_INSTANCE_NAME = 'authenticator';

    public const AUTHENTICATOR_SERVICE_TOKEN_API_HEADER_KEY = 'Owo-Api-Token';

    public const AUTHENTICATOR_SERVICE_TOKEN_WEB_HEADER_KEY = 'Owo-Web-Token';

    public const AUTHENTICATOR_SERVICE_DEFAULT_TOKEN_TYPE = 'Bearer';

    public function authenticate(OwoServerRequestInterface $request, array $options = []): ?User
    {
        return $this->authRequestUsingPassword($request, $options) ??
            $this->authRequestUsingToken($request);
    }

    public function authRequestUsingPassword(OwoServerRequestInterface $request, array $options = []): ?User
    {
        return $this->authRequestUsingCredentials($request, $options);
    }

    public function authRequestUsingCredentials(OwoServerRequestInterface $request, array $options = []): ?User
    {
        return $this->authUsingCredentials($request->getInputs(), $options);
    }

    public function authRequestUsingToken(OwoServerRequestInterface $request): ?User
    {
        if (true !== \is_null($token = $this->searchRequestToken($request))) {
            return $this->authUsingToken($token);
        }
        return null;
    }

    public function authUsingCredentials(array $credentials, array $options = []): ?User
    {
        list($email, $username, $password) = [
            $credentials['email'] ?? null, $credentials['username'] ?? null,
            $credentials['password'] ?? null,
        ];

        if ((true !== \is_null($email)) && (true !== \is_null($password))) {
            return $this->authUsingPassword($email, $password, $options);
        }

        if ((true !== \is_null($username)) && (true !== \is_null($password))) {
            if (1 === \count($users = User::findWhere(['username' => $username]))) {
                return $this->rehashUserPassword($users[0], $password, $options);
            }
        }
        return null;
    }

    public function authUsingPassword(string $email, string $password, array $options = []): ?User
    {
        if (1 === \count($users = User::findWhere(['email' => $email]))) {
            return $this->rehashUserPassword($users[0], $password, $options);
        }
        return null;
    }

    public function authUsingToken(string $token): ?User
    {
        $matches = $this->splitTokenAsTypeAndToken($token);
        list($type, $token) = [ $matches['type'], $matches['token'], ];

        switch ($type) {
            case 'Bearer': return $this->authUsingBearerToken($token); break;
            case 'Basic': return $this->authUsingBasicToken($token); break;
            default:
                $format = ('Unsupported Token Type [%s] Found');
                static::throwRuntimeException(\sprintf($format, $type));
            break;
        }
    }

    public function authUsingBearerToken(string $token): ?User
    {
        if (true !== \is_null($token = $this->findToken($token))) {
            return $this->getTokenUser($token);
        }
        return null;
    }

    public function authUsingBasicToken(string $token): ?User
    {
        if (true === \is_null($match = OwoHelperDecoder::decodeBase64($token))) {
            $message = \sprintf('Decoding Base64 Basic Token [%s] Failed', $token);
            static::throwRuntimeException($message);
        }

        if (2 > \count($matches = \explode(':', $match, 2))) {
            static::throwRuntimeException('Malformed Basic Token Found');
        }
        return $this->authUsingPassword($matches[0], $matches[1]);
    }

    public function rehashUserPassword(User $user, string $password, array $options = []): ?User
    {
        if (true === OwoHelperPassword::verify($password, $hash = $user->get('password'))) {
            if (true === OwoHelperPassword::needsRehash($hash, $options)) {
                return $user->set('password', OwoHelperPassword::hash($password, $options))->update();
            }
            return $user;
        }
        return null;
    }

    public function acquireUserToken(User $user, array $inputs = []): ?Token
    {
        if (true === \is_null($token = $this->getUserCurrentToken($user))) {
            return OwoRegistrarService::getInstance()->registerNewToken($user, $inputs);
        }
        return $token;
    }

    public function getUserCurrentToken(User $user): ?Token
    {
        $matches = ['is_revoked' => false, 'user_id' => $user->getId()];
        foreach (Token::findWhere($matches) as $token) {
            if (true !== $token->hasExpiredNow()) return $token;
            $token->set('is_revoked', true)->update();
        }
        return null;
    }

    public function revokeToken(string $token): ?Token
    {
        if (true !== \is_null($token = $this->findToken($token))) {
            return $token->set('is_revoked', true)->update();
        }
        return null;
    }

    public function getTokenUser(Token $token): ?User
    {
        if ((false === $token->hasBeenRevoked()) && (false === $token->hasExpiredNow())) {
            return User::findOne($token->get('user_id'));
        }
        return null;
    }

    public function searchRequestToken(OwoServerRequestInterface $request): ?string
    {
        return $request->getHeader(static::AUTHENTICATOR_SERVICE_TOKEN_WEB_HEADER_KEY) ??
            $request->getHeader(static::AUTHENTICATOR_SERVICE_TOKEN_API_HEADER_KEY) ??
            $request->getHeader('Authorization') ??
            $request->getHeader('Proxy-Authorization');
    }

    protected function findToken(string $token): ?Token
    {
        if (1 === \count($tokens = Token::findWhere(['token' => $token]))) {
            return $tokens[0];
        }
        return null;
    }

    protected function splitTokenAsTypeAndToken(string $token): array
    {
        $matches = \explode(' ', $token, 2);
        if (2 > \count($matches)) {
            return [
                'type' => static::AUTHENTICATOR_SERVICE_DEFAULT_TOKEN_TYPE,
                'token' => $matches[0],
            ];
        }
        return [ 'type' => $matches[0], 'token' => $matches[1], ];
    }

    protected static function getInstanceName(): string
    {
        return static::AUTHENTICATOR_SERVICE_INSTANCE_NAME;
    }
}
