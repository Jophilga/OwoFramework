<?php

namespace framework\libraries\owo\classes\Clients;

use framework\libraries\owo\interfaces\Clients\OwoClientInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeHeadersTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeOptionsTrait;


abstract class OwoClient implements OwoClientInterface
{
    use OwoTakeArrayKeyMixeHeadersTrait;
    use OwoTakeArrayKeyMixeOptionsTrait;

    public function __construct(array $headers = [], array $options = [])
    {
        $this->setHeaders($headers)->setOptions($options);
    }

    abstract public function send(OwoServerRequestInterface $request, array $options = []): ?OwoServerResponseInterface;

    public function getNormalizedHeaders(): array
    {
        return $this->normalizeHeaders($this->headers);
    }

    public function normalizeHeaders(array $headers): array
    {
        $normalized_headers = [];
        foreach ($headers as $key => $value) {
            if (true === \is_string($key)) {
                $normalized_headers[] = \sprintf('%s: %s', $key, $value);
            }
            else $normalized_headers[] = $value;
        }
        return $normalized_headers;
    }
}
