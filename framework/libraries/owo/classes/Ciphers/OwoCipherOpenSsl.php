<?php

namespace framework\libraries\owo\classes\Ciphers;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Ciphers\OwoCipher;

use framework\libraries\owo\interfaces\Ciphers\OwoCipherOpenSslInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeParamsTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringSecretTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoCipherOpenSsl extends OwoCipher implements OwoCipherOpenSslInterface
{
    use OwoTakeArrayKeyMixeParamsTrait;
    use OwoTakeStringSecretTrait;

    use OwoMakeCommonThrowerTrait;

    public function __construct(string $secret, array $params = [])
    {
        $this->setSecret($secret)->setParams($params);
    }

    public function encrypt(string $data, array &$params = []): ?string
    {
        $params = $this->mergeParams($params);
        $encryption = static::encryptUsingSecret($data, $this->secret, $params);
        return $encryption;
    }

    public function decrypt(string $data, array $params = []): ?string
    {
        $params = $this->mergeParams($params);
        $decryption = static::decryptUsingSecret($data, $this->secret, $params);
        return $decryption;
    }

    protected function mergeParams(array ...$params): array
    {
        return OwoHelperArray::mergeProperly($this->params, ...$params);
    }

    protected static function generateSecretUsingUname(bool $binary = true): string
    {
        return \openssl_digest(\php_uname(), 'MD5', $binary);
    }

    public static function encryptUsingHiddenSecret(string $data, array &$params = []): ?string
    {
        $params['method'] = \strval($params['method'] ?? 'BF-CBC');
        $secret = $params['secret'] = static::generateSecretUsingUname(true);
        return static::encryptUsingSecret($data, $secret, $params);
    }

    public static function encryptUsingPublicKey(string $data, string $public_key, array $params = []): ?string
    {
        $encryption = '';
        $padding = \intval($params['padding'] ?? \OPENSSL_PKCS1_PADDING);
        if (true === \openssl_public_encrypt($data, $encryption, $public_key, $padding)) {
            return $encryption;
        }
        return null;
    }

    public static function encryptUsingPrivateKey(string $data, string $private_key, array $params = []): ?string
    {
        $encryption = '';
        $padding = \intval($params['padding'] ?? \OPENSSL_PKCS1_PADDING);
        if (true === \openssl_private_encrypt($data, $encryption, $private_key, $padding)) {
            return $encryption;
        }
        return null;
    }

    public static function encryptUsingSecret(string $data, string $secret, array &$params = []): ?string
    {
        $method = \strval($params['method'] ?? 'AES-128-CTR');
        $params['iv'] = \strval($params['iv'] ?? static::generateRandomMethodIv($method));
        $options = \intval($params['options'] ?? 0);
        $encryption = \openssl_encrypt($data, $method, $secret, $options, $params['iv']);
        return (false !== $encryption) ? $encryption : null;
    }

    public static function decryptUsingHiddenSecret(string $data, array &$params = []): ?string
    {
        $params['method'] = \strval($params['method'] ?? 'BF-CBC');
        $secret = $params['secret'] = $params['secret'] ?? static::generateSecretUsingUname(true);
        return static::decryptUsingSecret($data, $secret, $params);
    }

    public static function decryptUsingPublicKey(string $data, string $public_key, array $params = []): ?string
    {
        $decryption = '';
        $padding = \intval($params['padding'] ?? \OPENSSL_PKCS1_PADDING);
        if (true === \openssl_public_decrypt($data, $decryption, $public_key, $padding)) {
            return $decryption;
        }
        return null;
    }

    public static function decryptUsingPrivateKey(string $data, string $private_key, array $params = []): ?string
    {
        $decryption = '';
        $padding = \intval($params['padding'] ?? \OPENSSL_PKCS1_PADDING);
        if (true === \openssl_private_decrypt($data, $decryption, $private_key, $padding)) {
            return $decryption;
        }
        return null;
    }

    public static function decryptUsingSecret(string $data, string $secret, array &$params = []): ?string
    {
        $method = \strval($params['method'] ?? 'AES-128-CTR');
        $params['iv'] = \strval($params['iv'] ?? static::generateRandomMethodIv($method));
        $options = \intval($params['options'] ?? 0);
        $decryption = \openssl_decrypt($data, $method, $secret, $options, $params['iv']);
        return (false !== $decryption) ? $decryption : null;
    }

    public static function generateKeysUsingSecret(string $secret, array $params = []): array
    {
        if (false === ($akey = \openssl_pkey_new($params))) {
            static::throwRuntimeException('Generating New Asymmeric Key Failed');
        }

        if (true !== \openssl_pkey_export($akey, $private_key, $secret)) {
            static::throwRuntimeException('Exporting Private Key From Asymmeric Key Failed');
        }

        if (false === ($details = \openssl_pkey_get_details($akey))) {
            static::throwRuntimeException('Getting Asymmeric Key Details Failed');
        }
        return ['public_key' => $details['key'], 'private_key' => $private_key];
    }

    public static function verify(string $data, string $signature, string $public_key, array $params = []): bool
    {
        $algorithm = $params['algorithm'] ?? \OPENSSL_ALGO_SHA256;
        $verification = (1 === \openssl_verify($data, $signature, $public_key, $algorithm));
        return $verification;
    }

    public static function sign(string $data, string $private_key, array $params = []): ?string
    {
        $algorithm = $params['algorithm'] ?? \OPENSSL_ALGO_SHA256;
        if (true === \openssl_sign($data, $signature, $private_key, $algorithm)) {
            return $signature;
        }
        return null;
    }

    public static function generateRandomMethodIv(string $method): string
    {
        return \openssl_random_pseudo_bytes(\openssl_cipher_iv_length($method));
    }
}
