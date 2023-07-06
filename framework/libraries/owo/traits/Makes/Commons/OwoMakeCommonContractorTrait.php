<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


trait OwoMakeCommonContractorTrait
{
    use OwoMakeCommonThrowerTrait;

    protected static $dicontainer = null;

    protected static function resolve(string $name, array $args = []): ?object
    {
        return static::ensureDIContainer()->instantiate($name, $args);
    }

    protected static function define($instance, $registry = null)
    {
        static::ensureDIContainer()->singleton($instance, $registry);
    }

    protected static function defaultInstance(): self
    {
        return static::ensureDIContainer()->construct(static::class);
    }

    protected static function ensureDIContainer(): OwoCasterDIContainerInterface
    {
        if (true === \is_null($dicontainer = static::getDIContainer())) {
            static::throwRuntimeException('Static DI Container Instance Not Found');
        }
        return $dicontainer;
    }

    public static function setDIContainer(OwoCasterDIContainerInterface $dicontainer)
    {
        static::$dicontainer = $dicontainer;
    }

    public static function getDIContainer(): ?OwoCasterDIContainerInterface
    {
        return static::$dicontainer;
    }

    public static function setInstance(object $instance)
    {
        if (true !== \is_a($instance, static::class, false)) {
            $message = \sprintf('Not Instance Of [%s] Found', static::class);
            static::throwRuntimeException($message);
        }
        static::define($instance, static::getInstanceName());
    }

    public static function getInstance(): self
    {
        $instance = static::resolve(static::getInstanceName());
        if (true === \is_null($instance)) {
            static::setInstance($instance = static::defaultInstance());
        }
        return $instance;
    }

    abstract protected static function getInstanceName(): string;
}
