<?php

namespace framework\libraries\owo\interfaces\Ciphers;


interface OwoCipherInterface
{
    public function encrypt(string $data, array &$params = []): ?string;

    public function decrypt(string $data, array $params = []): ?string;
}
