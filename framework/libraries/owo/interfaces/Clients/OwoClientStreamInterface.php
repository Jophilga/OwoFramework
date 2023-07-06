<?php

namespace framework\libraries\owo\interfaces\Clients;

use framework\libraries\owo\interfaces\Clients\OwoClientInterface;


interface OwoClientStreamInterface extends OwoClientInterface
{
    public function executeStream(string $url, array $options = [], array $params = []): ?string;

    public function getStreamContext(array $options = [], array $params = []): \resource;
}
