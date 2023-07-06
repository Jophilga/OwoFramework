<?php

namespace framework\libraries\owo\interfaces\Servers;

use framework\libraries\owo\interfaces\Stackers\OwoStackerOnionInterface;
use framework\libraries\owo\interfaces\Casters\OwoStackerOnionImitorInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


interface OwoServerInterface
{
    public function middlewares(array $middlewares): self;

    public function middleware(callable $middleware): self;

    public function listen(string $host, int $port = 80): self;

    public function setHostUsingConfigs($default = 'localhost'): self;

    public function getHostUsingConfigs($default = 'localhost'): string;

    public function setPortUsingConfigs($default = 80): self;

    public function getPortUsingConfigs($default = 80): int;

    public function getClientHostUsingConfigs($default = null): string;

    public function getClientPortUsingConfigs($default = null): int;

    public function serves(OwoServerRequestInterface $request): bool;

    public function hasHttpsOnUsingConfigs(): bool;

    public function setStacker(OwoStackerOnionInterface $stacker): self;

    public function getStacker(): ?OwoStackerOnionInterface;

    public function setHost(string $host): self;

    public function getHost(): ?string;

    public function setPort(int $port): self;

    public function getPort(): ?int;

    public function setConfigs(array $configs): self;

    public function emptyConfigs(): self;

    public function addConfigs(array $configs): self;

    public function addConfig($key, $value): self;

    public function getConfigs(): array;

    public function hasConfig($key): bool;

    public function removeConfig($key): self;

    public function getConfig($key, $default = null);
}
