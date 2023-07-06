<?php

namespace framework\libraries\owo\interfaces\Guards;


interface OwoGuardKeypairInterface
{
    public function exportAsArray(): array;

    public function sign(string $data, array $params = []): ?string;

    public function verify(string $data, string $signature, array $params = []): bool;

    public function encryptWithPublicKey(string $data, array $params = []): ?string;

    public function encryptWithPrivateKey(string $data, array $params = []): ?string;

    public function decryptWithPublicKey(string $data, array $params = []): ?string;

    public function decryptWithPrivateKey(string $data, array $params = []): ?string;

    public function setPrivateKey(string $private_key): self;

    public function getPrivateKey(): ?string;

    public function setPublicKey(string $public_key): self;

    public function getPublicKey(): ?string;
}
