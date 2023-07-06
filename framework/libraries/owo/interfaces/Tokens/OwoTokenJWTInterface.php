<?php

namespace framework\libraries\owo\interfaces\Tokens;

use framework\libraries\owo\interfaces\Guards\OwoGuardKeypairInterface;


interface OwoTokenJWTInterface
{
    public function getToken(bool $decode = false): string;

    public function validates(string $token, array &$payloads = null): bool;

    public function sign(string $data): ?string;

    public function verifies(string $signature, string $data): bool;

    public function isOperable(string &$function = null, string &$algorithm = null): bool;

    public function verifiesWithOpenSSL(string $signature, string $data, string $algorithm): bool;

    public function verifiesWithHashHmac(string $signature, string $data, string $algorithm): bool;

    public function signWithOpenSSL(string $data, string $algorithm): ?string;

    public function signWithHashHmac(string $data, string $algorithm): string;

    public function getHeaders(): array;

    public function setSecret(string $secret): self;

    public function getSecret(): ?string;

    public function setMethod(string $method): self;

    public function getMethod(): ?string;

    public function setKeypair(OwoGuardKeypairInterface $keypair): self;

    public function getKeypair(): ?OwoGuardKeypairInterface;

    public function setPayloads(array $payloads): self;

    public function emptyPayloads(): self;

    public function addPayloads(array $payloads): self;

    public function addPayload($key, $value): self;

    public function getPayloads(): array;

    public function hasPayload($key): bool;

    public function removePayload($key): self;

    public function getPayload($key, $default = null);
}
