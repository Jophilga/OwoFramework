<?php

namespace framework\libraries\owo\interfaces\Swings;

use framework\libraries\owo\interfaces\Swings\OwoSwingMapperInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;


interface OwoSwingRouterInterface extends OwoSwingMapperInterface
{
    public function dispatch(OwoServerRequestInterface $request): ?OwoServerResponseInterface;

    public function route(OwoServerRequestInterface $request, array &$params = null): ?OwoSwingRouteInterface;

    public function crudmap(string $scope, $target, bool $forms = false): self;

    public function web(callable $callback, array $args = []): self;

    public function api(callable $callback, array $args = []): self;

    public function post(string $path, $action, string $name = null): self;

    public function get(string $path, $action, string $name = null): self;

    public function patch(string $path, $action, string $name = null): self;

    public function put(string $path, $action, string $name = null): self;

    public function delete(string $path, $action, string $name = null): self;

    public function options(string $path, $action, string $name = null): self;

    public function url(string $name, array $params = []): ?string;
}
