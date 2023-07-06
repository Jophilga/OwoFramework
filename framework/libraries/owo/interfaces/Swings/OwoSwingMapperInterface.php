<?php

namespace framework\libraries\owo\interfaces\Swings;

use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;


interface OwoSwingMapperInterface
{
    public function mapRoutesFromJSON(string $filejson): self;

    public function map(string $method, string $path, $action, string $name = null): self;

    public function setRoutes(array $routes): self;

    public function emptyRoutes(): self;

    public function mapRoutes(array $routes): self;

    public function mapRoute($route, $path = null): self;

    public function addRoutes(array $routes): self;

    public function addRoute(OwoSwingRouteInterface $route): self;

    public function hasRoute(string $name): bool;

    public function getRoute(string $name, $default = null): ?OwoSwingRouteInterface;

    public function removeRoute(string $name): self;

    public function getRoutes(): array;

    public function removeMethodRoutes(string $method, string $path = null): self;

    public function getMethodRoutes(string $method): array;

    public function scope(string $scope, callable $callback, array $args = []): self;

    public function group(array $group, callable $callback, array $args = []): self;

    public function perform(callable $callback, array $args = []): self;

    public function setPrefix(string $prefix): self;

    public function getPrefix(): ?string;
}
