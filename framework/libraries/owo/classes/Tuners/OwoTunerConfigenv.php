<?php

namespace framework\libraries\owo\classes\Tuners;

use framework\libraries\owo\classes\Tuners\OwoTunerConfigurator;

use framework\libraries\owo\interfaces\Tuners\OwoTunerConfigenvInterface;


class OwoTunerConfigenv extends OwoTunerConfigurator implements OwoTunerConfigenvInterface
{
    public function __construct(array $configs = [], array $ignores = [])
    {
        parent::__construct($configs, $ignores);
    }

    public function publish(): self
    {
        static::setEnvs($this->getConfigs());
        return $this;
    }

    public static function setEnvs(array $envs): array
    {
        $returns = [];
        foreach ($envs as $name => $value) {
            $returns[$name] = static::setEnv($name, $value);
        }
        return $returns;
    }

    public static function setEnv(string $name, string $value): bool
    {
        return (true === \putenv(\sprintf('%s=%s', $name, $value)));
    }

    public static function getEnvs(): array
    {
        return \getenv() ?: [];
    }

    public static function getEnv(string $name, $default = null)
    {
        return \getenv($name, true) ?: \getenv($name) ?: $default;
    }
}
