<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoHelperPassword extends OwoHelper
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_PASSWORD_DEFAULT_ALGO = \PASSWORD_BCRYPT;

    public const HELPER_PASSWORD_ADDITIONALS = [];

    public static function hash(string $password, array $options = []): string
    {
        $hash = \password_hash($password, static::getOptionAlgo($options), $options);
        if (false === $hash) {
            static::throwRuntimeException('Hashing Password Failed');
        }
        return $hash;
    }

    public static function verify(string $password, string $hash): bool
    {
        return (true === \password_verify($password, $hash));
    }

    public static function needsRehash(string $hash, array $options = []): bool
    {
        return (true === \password_needs_rehash($hash, static::getOptionAlgo($options), $options));
    }

    public static function getHashInfos(string $hash): array
    {
        return \password_get_info($hash);
    }

    public static function getAvailableAlgos(): array
    {
        return \password_algos();
    }

    protected static function getOptionAlgo(array $options): string
    {
        return $options['algo'] ?? static::HELPER_PASSWORD_DEFAULT_ALGO;
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_PASSWORD_ADDITIONALS;
    }
}
