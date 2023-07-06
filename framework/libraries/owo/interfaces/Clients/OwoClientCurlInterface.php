<?php

namespace framework\libraries\owo\interfaces\Clients;

use framework\libraries\owo\interfaces\Clients\OwoClientInterface;


interface OwoClientCurlInterface extends OwoClientInterface
{
    public function resetReponseHeaders(): self;

    public function initializeCurl(): self;

    public function executeCurl(array $options = [], string &$error = null): ?string;

    public function resetCurl(): self;

    public function resetCurlProperly(): self;

    public function closeCurl(): self;

    public function hasInitializedCurl(): bool;

    public function getCurlInfo(int $option, $default = null);

    public function getCurlInfos(): array;

    public function getCurlError(): ?string;

    public function createCurl(): ?\CurlHandle;

    public function setUrl(string $url): self;

    public function getUrl(): ?string;

    public function setCurl(\CurlHandle $curl): self;

    public function getCurl(): ?\CurlHandle;
}
