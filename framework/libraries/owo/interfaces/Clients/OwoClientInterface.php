<?php

namespace framework\libraries\owo\interfaces\Clients;

use framework\libraries\owo\interfaces\Commons\OwoCommonSRSenderInterface;


interface OwoClientInterface extends OwoCommonSRSenderInterface
{
    public function getNormalizedHeaders(): array;

    public function normalizeHeaders(array $headers): array;

    public function setHeaders(array $headers): self;

    public function emptyHeaders(): self;

    public function addHeaders(array $headers): self;

    public function addHeader($key, $value): self;

    public function getHeaders(): array;

    public function hasHeader($key): bool;

    public function removeHeader($key): self;

    public function getHeader($key, $default = null);

    public function setOptions(array $options): self;

    public function emptyOptions(): self;

    public function addOptions(array $options): self;

    public function addOption($key, $value): self;

    public function getOptions(): array;

    public function hasOption($key): bool;

    public function removeOption($key): self;

    public function getOption($key, $default = null);
}
