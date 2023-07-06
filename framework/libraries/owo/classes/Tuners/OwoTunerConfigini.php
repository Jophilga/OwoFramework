<?php

namespace framework\libraries\owo\classes\Tuners;

use framework\libraries\owo\classes\Tuners\OwoTunerConfigurator;

use framework\libraries\owo\interfaces\Tuners\OwoTunerConfiginiInterface;


class OwoTunerConfigini extends OwoTunerConfigurator implements OwoTunerConfiginiInterface
{
    public function __construct(array $configs = [], array $ignores = [])
    {
        parent::__construct($configs, $ignores);
    }

    public function publish(): self
    {
        static::setInis($this->getConfigs());
        return $this;
    }

    public static function setInis(array $inis): array
    {
        $returns = [];
        foreach ($inis as $name => $value) {
            $returns[$name] = static::setIni($name, $value);
        }
        return $returns;
    }

    public static function setIni(string $name, string $value): bool
    {
        return (false !== \ini_set($name, $value));
    }

    public static function getInis(): array
    {
        return \ini_get_all() ?: [];
    }

    public static function getIni(string $name, $default = null): ?string
    {
        return \ini_get($name) ?: $default;
    }

    public static function resetIni(string $name)
    {
        \ini_restore($name);
    }
}
