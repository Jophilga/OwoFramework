<?php

namespace framework\libraries\owo\classes\Guards;

use framework\libraries\owo\classes\Ciphers\OwoCipherOpenSsl;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Guards\OwoGuardKeypairInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPrivateKeyTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPublicKeyTrait;


class OwoGuardKeypair implements OwoGuardKeypairInterface
{
    use OwoTakeStringPrivateKeyTrait;
    use OwoTakeStringPublicKeyTrait;

    public function __construct(string $private_key, string $public_key)
    {
        $this->setPrivateKey($private_key)->setPublicKey($public_key);
    }

    public function exportAsArray(): array
    {
        return ['private_key' => $this->private_key, 'public_key' => $this->public_key];
    }

    public function sign(string $data, array $params = []): ?string
    {
        return OwoCipherOpenSsl::sign($data, $this->private_key, $params);
    }

    public function verify(string $data, string $signature, array $params = []): bool
    {
        return (true === OwoCipherOpenSsl::verify($data, $signature, $this->public_key, $params));
    }

    public function encryptWithPublicKey(string $data, array $params = []): ?string
    {
        return OwoCipherOpenSsl::encryptUsingPublicKey($data, $this->public_key, $params);
    }

    public function encryptWithPrivateKey(string $data, array $params = []): ?string
    {
        return OwoCipherOpenSsl::encryptUsingPrivateKey($data, $this->private_key, $params);
    }

    public function decryptWithPublicKey(string $data, array $params = []): ?string
    {
        return OwoCipherOpenSsl::decryptUsingPublicKey($data, $this->public_key, $params);
    }

    public function decryptWithPrivateKey(string $data, array $params = []): ?string
    {
        return OwoCipherOpenSsl::decryptUsingPrivateKey($data, $this->private_key, $params);
    }

    public static function ensureKeypair($keypair): OwoGuardKeypairInterface
    {
        if (true === \is_array($keypair)) return static::fromParams($keypair);
        elseif (true === \is_subclass_of($keypair, OwoGuardKeypairInterface::class)) {
            return $keypair;
        }
        static::throwRuntimeException('Invalid Keypair Found');
    }

    public static function fromSecret(string $secret, array $params = []): self
    {
        $keys = OwoCipherOpenSsl::generateKeysUsingSecret($secret, $params);
        return static::fromParams($keys);
    }

    public static function fromParams(array $params): self
    {
        if (true !== OwoHelperArray::hasSetKey($params, 'public_key')) {
            static::throwRuntimeException('Required Param [public_key] Not Found');
        }
        elseif (true !== OwoHelperArray::hasSetKey($params, 'private_key')) {
            static::throwRuntimeException('Required Param [private_key] Not Found');
        }

        list($private_key, $public_key) = [
            \strval($params['private_key']), \strval($params['public_key']),
        ];
        return new OwoGuardKeypair($private_key, $public_key);
    }
}
