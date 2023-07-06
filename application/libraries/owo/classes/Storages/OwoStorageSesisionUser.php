<?php

namespace application\libraries\owo\classes\Storages;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use application\libraries\owo\classes\Storages\OwoStorageSession;
use application\models\owo\User;

use application\libraries\owo\interfaces\Storages\OwoStorageSesisionUserInterface;


class OwoStorageSesisionUser extends OwoStorageSession implements OwoStorageSesisionUserInterface
{
    protected $last_auth_user_id = null;

    public function __construct(OwoSessionPrefixInterface $session)
    {
        parent::__construct($session);
    }

    public function getLastAuthUser($default = null): ?User
    {
        if (true !== \is_null($this->last_auth_user_id)) {
            return $this->getAuthUser($this->last_auth_user_id, $default);
        }
        return null;
    }

    public function addAuthUser(User $user): self
    {
        if (true !== \is_null($id = $user->getId())) {
            $this->session->save($this->prepareAuthUserKey($id), $user);
            $this->last_auth_user_id = $id;
        }
        return $this;
    }

    public function deleteAuthUsers(): self
    {
        foreach ($this->getAuthUsers() as $user) {
            if (true !== \is_null($id = $user->getId())) {
                $this->deleteAuthUser($id);
            }
        }
        return $this;
    }

    public function deleteAuthUser($id): self
    {
        $this->session->remove($this->prepareAuthUserKey($id));
        return $this;
    }

    public function hasAuthUser($id = null): bool
    {
        if (true !== \is_null($id)) {
            return (true === $this->session->has($this->prepareAuthUserKey($id)));
        }
        return (true !== empty($this->getAuthUsers()));
    }

    public function getAuthUser($id, $default = null): ?User
    {
        return $this->session->get($this->prepareAuthUserKey($id), $default);
    }

    public function getAuthUsers(): array
    {
        $search = \sprintf('%s.auth.', $this->session->getPrefix());
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    protected function prepareAuthUserKey($key): string
    {
        return \sprintf('auth.%s', \strval($key));
    }
}
