<?php

namespace framework\libraries\owo\classes\Ciphers;

use framework\libraries\owo\interfaces\Ciphers\OwoCipherInterface;


abstract class OwoCipher implements OwoCipherInterface
{
    abstract public function encrypt(string $data, array &$params = []): ?string;

    abstract public function decrypt(string $data, array $params = []): ?string;
}
