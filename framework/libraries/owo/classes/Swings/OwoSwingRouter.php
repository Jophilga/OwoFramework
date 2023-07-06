<?php

namespace framework\libraries\owo\classes\Swings;

use framework\libraries\owo\classes\Swings\OwoSwingMapper;
use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Swings\OwoSwingRoute;

use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Swings\OwoSwingRouterInterface;
use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use framework\libraries\owo\traits\Takes\Mixes\OwoTakeMixeGatewayTrait;


class OwoSwingRouter extends OwoSwingMapper implements OwoSwingRouterInterface, OwoCoreMiddlewareInterface
{
    use OwoMakeCommonThrowerTrait;

    use OwoTakeMixeGatewayTrait;

    public const SWING_ROUTER_CRUD_ROUTES_FORMS = [
        'GET' => ['/make' => 'make', '/{id}/edit' => 'edit'],
    ];

    public const SWING_ROUTER_CRUD_ROUTES = [
        'POST' => ['/' => 'create'],
        'GET' => ['/' => 'index', '/{id}' => 'search'],
        'PUT|PATCH' => ['/{id}' => 'update'],
        'DELETE' => ['/{id}' => 'delete'],
    ];

    public const SWING_ROUTER_SCOPE_WEB = '/';

    public const SWING_ROUTER_SCOPE_API = '/api/';

    public function __construct(array $routes = [], string $prefix = '/')
    {
        parent::__construct($routes, $prefix);
    }

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $response = $this->dispatch($request);
        if (true === is_null($response)) {
            return OwoServerResponse::ensureResponse($next($request));
        }
        return $response;
    }

    public function dispatch(OwoServerRequestInterface $request): ?OwoServerResponseInterface
    {
        if (true !== is_null($route = $this->route($request, $params))) {
            $response = \call_user_func($route->getHandler(), $request->map($route, $params));
            return OwoServerResponse::ensureResponse($response);
        }
        return null;
    }

    public function route(OwoServerRequestInterface $request, array &$params = null): ?OwoSwingRouteInterface
    {
        $method = $request->getMethod();
        foreach ($this->getMethodRoutes($method) as $path => $route) {
            if (true === $route->covers($request, $params)) return $route;
        }

        if (true !== is_null($this->gateway)) {
            $route = $this->createRoute($method, $request->path(), $this->gateway);
            if (true === $route->covers($request, $params)) return $route;
        }
        return null;
    }

    public function crudmap(string $scope, $target, bool $forms = false): self
    {
        $this->ensureClassOrObject($target);
        return $this->scope($scope, function ($router) use ($target, $forms) {
            $classname = (true === \is_string($target)) ? $target : \get_class($target);

            if (true === $forms) {
                foreach (static::SWING_ROUTER_CRUD_ROUTES_FORMS as $method => $matches) {
                    foreach ($matches as $path => $action) {
                        $name = \sprintf('%s@%s', $classname, $action);
                        $router->map($method, $path, [$target, $action], $name);
                    }
                }
            }

            foreach (static::SWING_ROUTER_CRUD_ROUTES as $method => $matches) {
                foreach ($matches as $path => $action) {
                    $name = \sprintf('%s@%s', $classname, $action);
                    $router->map($method, $path, [$target, $action], $name);
                }
            }
        });
    }

    public function web(callable $callback, array $args = []): self
    {
        $scope = static::SWING_ROUTER_SCOPE_WEB;
        return $this->scope($scope, function ($router) use ($callback, $args) {
            $router->perform($callback, $args);
        });
    }

    public function api(callable $callback, array $args = []): self
    {
        $scope = static::SWING_ROUTER_SCOPE_API;
        return $this->scope($scope, function ($router) use ($callback, $args) {
            $router->perform($callback, $args);
        });
    }

    public function post(string $path, $action, string $name = null): self
    {
        return $this->map('POST', $path, $action, $name);
    }

    public function get(string $path, $action, string $name = null): self
    {
        return $this->map('GET', $path, $action, $name);
    }

    public function patch(string $path, $action, string $name = null): self
    {
        return $this->map('PATCH', $path, $action, $name);
    }

    public function put(string $path, $action, string $name = null): self
    {
        return $this->map('PUT', $path, $action, $name);
    }

    public function delete(string $path, $action, string $name = null): self
    {
        return $this->map('DELETE', $path, $action, $name);
    }

    public function options(string $path, $action, string $name = null): self
    {
        return $this->map('OPTIONS', $path, $action, $name);
    }

    public function url(string $name, array $params = []): ?string
    {
        if (true !== is_null($route = $this->getRoute($name))) return $route->url($params);
        return null;
    }

    protected function ensureClassOrObject($target): self
    {
        if (true !== \is_object($target)) {
            if ((true !== \is_string($target)) || (true !== \class_exists($target))) {
                $message = \sprintf('Wrong Target Type [%s] Found', \gettype($target));
                static::throwRuntimeException($message);
            }
        }
        return $this;
    }
}
