<?php

namespace application\libraries\owo\interfaces\Storages;

use application\models\owo\AccessToken;
use application\models\owo\Token;

use application\libraries\owo\interfaces\Storages\OwoStorageSessionInterface;


interface OwoStorageSesisionTokenInterface extends OwoStorageSessionInterface
{
    public function addAccessToken(AccessToken $access_token): self;

    public function deleteAccessTokens(): self;

    public function deleteAccessToken($id): self;

    public function hasAccessToken($id = null): bool;

    public function getAccessToken($id, $default = null): ?AccessToken;

    public function getAccessTokens(): array;

    public function addToken(Token $token): self;

    public function deleteTokens(): self;

    public function deleteToken($id): self;

    public function hasToken($id): bool;

    public function getToken($id, $default = null): ?Token;

    public function getTokens(): array;
}
