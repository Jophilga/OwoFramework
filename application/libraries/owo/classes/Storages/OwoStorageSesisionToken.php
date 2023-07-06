<?php

namespace application\libraries\owo\classes\Storages;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use application\libraries\owo\classes\Storages\OwoStorageSession;
use application\models\owo\Token;
use application\models\owo\AccessToken;

use application\libraries\owo\interfaces\Storages\OwoStorageSesisionTokenInterface;


class OwoStorageSesisionToken extends OwoStorageSession implements OwoStorageSesisionTokenInterface
{
    public function __construct(OwoSessionPrefixInterface $session)
    {
        parent::__construct($session);
    }

    public function addAccessToken(AccessToken $access_token): self
    {
        if (true !== \is_null($id = $access_token->getId())) {
            $this->session->save($this->prepareAccessTokenKey($id), $access_token);
        }
        return $this;
    }

    public function deleteAccessTokens(): self
    {
        foreach ($this->getAccessTokens() as $access_token) {
            if (true !== \is_null($id = $access_token->getId())) {
                $this->deleteAccessToken($id);
            }
        }
        return $this;
    }

    public function deleteAccessToken($id): self
    {
        $this->session->remove($this->prepareAccessTokenKey($id));
        return $this;
    }

    public function hasAccessToken($id = null): bool
    {
        if (true !== \is_null($id)) {
            return (true === $this->session->has($this->prepareAccessTokenKey($id)));
        }
        return (true !== empty($this->getAccessTokens()));
    }

    public function getAccessToken($id, $default = null): ?AccessToken
    {
        return $this->session->get($this->prepareAccessTokenKey($id), $default);
    }

    public function getAccessTokens(): array
    {
        $search = \sprintf('%s.access_token.', $this->session->getPrefix());
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    public function addToken(Token $token): self
    {
        if (true !== is_null($id = $token->getId())) {
            return $this->session->save($this->prepareTokenKey($id), $token);
        }
        return $this;
    }

    public function deleteTokens(): self
    {
        foreach ($this->getTokens() as $token) {
            if (true !== \is_null($id = $token->getId())) {
                $this->deleteToken($id);
            }
        }
        return $this;
    }

    public function deleteToken($id): self
    {
        $this->session->remove($this->prepareTokenKey($id));
        return $this;
    }

    public function hasToken($id): bool
    {
        if (true !== \is_null($id)) {
            return (true === $this->session->has($this->prepareTokenKey($id)));
        }
        return (true !== empty($this->getTokens()));
    }

    public function getToken($id, $default = null): ?Token
    {
        return $this->session->get($this->prepareTokenKey($id), $default);
    }

    public function getTokens(): array
    {
        $search = \sprintf('%s.token.', $this->session->getPrefix());
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    protected function prepareTokenKey($key): string
    {
        return \sprintf('token.%s', \strval($key));
    }

    protected function prepareAccessTokenKey($key): string
    {
        return \sprintf('access_token.%s', \strval($key));
    }
}
