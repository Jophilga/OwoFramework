<?php

namespace framework\libraries\owo\classes\Tokens;

use framework\libraries\owo\classes\Helpers\OwoHelperDecoder;
use framework\libraries\owo\classes\Helpers\OwoHelperEncoder;

use framework\libraries\owo\interfaces\Guards\OwoGuardKeypairInterface;
use framework\libraries\owo\interfaces\Tokens\OwoTokenJWTInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringSecretTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixePayloadsTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringMethodTrait;


class OwoTokenJWT implements OwoTokenJWTInterface
{
    use OwoTakeStringSecretTrait;
    use OwoTakeArrayKeyMixePayloadsTrait;
    use OwoTakeStringMethodTrait;

    protected $keypair = null;

    public const TOKEN_JWT_SUPPORTED_METHODS = [
        'ES256' => ['openssl', 'SHA256'], 'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'], 'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'], 'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
    ];

    public const TOKEN_JWT_TYP = 'JWT';

    public function __construct(array $payloads = [], string $method = 'HS256')
    {
        $this->setPayloads($payloads)->setMethod($method);
    }

    public function setKeypair(OwoGuardKeypairInterface $keypair): self
    {
        $this->keypair = $keypair;
        return $this;
    }

    public function getKeypair(): ?OwoGuardKeypairInterface
    {
        return $this->keypair;
    }

    public function getToken(bool $decode = false): string
    {
        if (true === $decode) {
            $secret = $this->ensureSecret();
            $this->setSecret($this->ensureDecodeBase64($secret));
            $token = $this->generateTokenString();
            $this->setSecret($secret);
            return $token;
        }
        return $this->generateTokenString();
    }

    public function validates(string $token, array &$payloads = null): bool
    {
        if (3 !== \count($segments = explode('.', $token))) {
            static::throwInvalidArgumentException('Wrong Token Segments Number Found');
        }

        list($base64_json_headers, $base64_json_payloads, $base64_sign) = $segments;
        $signature = $this->ensureDecodeBase64($base64_sign);

        $data = \sprintf('%s.%s', $base64_json_headers, $base64_json_payloads);
        if (true === $this->verifies($signature, $data)) {
            $json_payloads = $this->ensureDecodeBase64($base64_json_payloads);
            $payloads = $this->ensureDecodeJSON($json_payloads);
            return true;
        }
        return false;
    }

    public function sign(string $data): ?string
    {
        if (true === $this->isOperable($function, $algorithm)) {
            switch ($function) {
                case 'openssl': return $this->signWithOpenSSL($data, $algorithm); break;
                case 'hash_hmac': return $this->signWithHashHmac($data, $algorithm); break;
                default:
                    $message = \sprintf('Sign Using [%s] Not Available', $function);
                    static::throwRuntimeException($message);
                break;
            }
        }
        return null;
    }

    public function verifies(string $signature, string $data): bool
    {
        if (true === $this->isOperable($function, $algorithm)) {
            switch ($function) {
                case 'openssl':
                    return (true === $this->verifiesWithOpenSSL($signature, $data, $algorithm));
                break;
                case 'hash_hmac':
                    return (true === $this->verifiesWithHashHmac($signature, $data, $algorithm));
                break;
                default:
                    $message = \sprintf('Verify using [%s] Not Available', $function);
                    static::throwRuntimeException($message);
                break;
            }
        }
        return false;
    }

    public function isOperable(string &$function = null, string &$algorithm = null): bool
    {
        if (true === isset(static::TOKEN_JWT_SUPPORTED_METHODS[$this->method])) {
            list($function, $algorithm) = static::TOKEN_JWT_SUPPORTED_METHODS[$this->method];
            return true;
        }
        return false;
    }

    public function verifiesWithOpenSSL(string $signature, string $data, string $algorithm): bool
    {
        return (1 === \openssl_verify($data, $signature, $this->ensurePublicKey(), $algorithm));
    }

    public function verifiesWithHashHmac(string $signature, string $data, string $algorithm): bool
    {
        return (true === \hash_equals($this->signWithHashHmac($data, $algorithm), $signature));
    }

    public function signWithOpenSSL(string $data, string $algorithm): ?string
    {
        $private_key = $this->ensurePrivateKey();
        if (true === \openssl_sign($data, $signature, $private_key, $algorithm)) {
            return $signature;
        }
        return null;
    }

    public function signWithHashHmac(string $data, string $algorithm): string
    {
        return \hash_hmac($algorithm, $data, $this->ensureSecret(), true);
    }

    public function getHeaders(): array
    {
        return ['alg' => $this->method, 'typ' => static::TOKEN_JWT_TYP];
    }

    protected function generateTokenString(): string
    {
        $segments = [
            $this->ensureEncodeToBase64($this->ensureEncodeToJSON($this->getHeaders())),
            $this->ensureEncodeToBase64($this->ensureEncodeToJSON($this->getPayloads())),
        ];

        $data = \implode('.', $segments);
        if (true === \is_null($signature = $this->sign($data))) {
            static::throwRuntimeException(\sprintf('Signing [%s] Failed', $data));
        }
        $segments[] = $this->ensureEncodeToBase64($signature);
        return \implode('.', $segments);
    }

    protected function ensureSecret(): string
    {
        if (true === \is_null($this->secret)) {
            static::throwRuntimeException('Defined JWT Secret Not Found');
        }
        return $this->secret;
    }

    protected function ensurePublicKey(): string
    {
        $public_key = $this->ensureKeypair()->getPublicKey();
        if (true === \is_null($public_key)) {
            static::throwRuntimeException('JWT Keypair Public Key Not Found');
        }
        return $public_key;
    }

    protected function ensurePrivateKey(): string
    {
        $private_key = $this->ensureKeypair()->getPrivateKey();
        if (true === \is_null($private_key)) {
            static::throwRuntimeException('JWT Keypair Private Key Not Found');
        }
        return $private_key;
    }

    protected function ensureKeypair(): OwoGuardKeypairInterface
    {
        if (true === \is_null($this->keypair)) {
            static::throwRuntimeException('Defined JWT Keypair Not Found');
        }
        return $this->keypair;
    }

    protected function ensureEncodeToBase64(string $data): string
    {
        $outcome = OwoHelperEncoder::encodeToBase64($data);
        $outcome = \strtr($outcome, ['+' => '-', '/' => '_']);
        return \strtr($outcome, ['=' => '']);
    }

    protected function ensureEncodeToJSON($data): string
    {
        $outcome = OwoHelperEncoder::encodeToJson($data, false);
        if (true === \is_null($outcome)) {
            static::throwRuntimeException('Encoding Data To JSON Failed');
        }
        return $outcome;
    }

    protected function ensureDecodeBase64(string $data): string
    {
        $outcome = OwoHelperDecoder::decodeBase64($data);
        if (true === \is_null($outcome)) {
            static::throwRuntimeException('Decoding Base64 Data Failed');
        }
        return \strtr($outcome, ['-' => '+', '_' => '/']);
    }

    protected function ensureDecodeJSON(string $data): array
    {
        $outcome = OwoHelperDecoder::decodeJsonToArray($data);
        if (true === \is_null($outcome)) {
            static::throwRuntimeException('Decoding JSON Data Failed');
        }
        return $outcome;
    }
}
