<?php

namespace framework\libraries\owo\interfaces\Commons;


interface OwoCommonOverlayerInterface
{
    public static function __callStatic(string $name, array $arguments);

    public static function callAsAccessory(string $name, array $arguments);

    public static function setAccessoryInstance(object $instance);

    public static function getAccessoryInstance(): ?object;
}
