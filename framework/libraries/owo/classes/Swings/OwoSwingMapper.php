<?php

namespace framework\libraries\owo\classes\Swings;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;
use framework\libraries\owo\classes\Swings\OwoSwingRoute;

use framework\libraries\owo\interfaces\Swings\OwoSwingMapperInterface;
use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPrefixTrait;


class OwoSwingMapper implements OwoSwingMapperInterface
{
    use OwoMakeCommonThrowerTrait;

    use OwoTakeStringPrefixTrait;

    protected $scope = '/';
    protected $group = [];
    protected $names = [];
    protected $routes = [];

    public function __construct(array $routes = [], string $prefix = '/')
    {
        $this->setRoutes($routes)->setPrefix($prefix);
    }

    public function mapRoutesFromJSON(string $filejson): self
    {
        $routes = OwoHelperBackrest::loadContentsJSON($filejson);
        if (true === \is_null($routes)) {
            static::throwRuntimeException(\sprintf('Load JSON [%s] Failed', $filejson));
        }
        return $this->mapRoutes($routes);
    }

    public function map(string $method, string $path, $action, string $name = null): self
    {
        return $this->addRoute(static::createRoute($method, $path, $action, $name));
    }

    public function setRoutes(array $routes): self
    {
        return $this->emptyRoutes()->mapRoutes($routes);
    }

    public function emptyRoutes(): self
    {
        $this->routes = [];
        return $this;
    }

    public function mapRoutes(array $routes): self
    {
        foreach ($routes as $path => $route) $this->mapRoute($route, $path);
        return $this;
    }

    public function mapRoute($route, $path = null): self
    {
        if (true === \is_subclass_of($route, OwoSwingRouteInterface::class)) {
            if ((true === \is_string($path)) && ($path !== $route->getPath())) {
                $route->setPath($path);
            }
            $this->addRoute($route);
        }
        elseif (true === \is_callable($route)) {
            $this->addRoute(static::createRoute('GET', $path, $route));
        }
        elseif (true === \is_array($route)) {
            if (true === OwoHelperArray::hasSetKey($route, 'path')) {
                return $this->addRoute(static::createRouteFromParams($route));
            }

            foreach ($route as $method => $action) {
                $this->addRoute(static::createRoute($method, $path, $action));
            }
        }
        elseif (true === \is_string($route)) {
            $this->addRoute(static::createRoute('GET', $path, $action));
        }
        else {
            $message = \sprintf('Bad Route Type [%s] Found', \gettype($route));
            static::throwRuntimeException($message);
        }
        return $this;
    }

    public function addRoutes(array $routes): self
    {
        foreach ($routes as $route) $this->addRoute($route);
        return $this;
    }

    public function addRoute(OwoSwingRouteInterface $route): self
    {
        $this->addNamedRoute($route = $this->prepareRoute($route));
        $methods = \explode(OwoSwingRoute::SWING_ROUTE_METHOD_DELIMITER, $route->getMethod());
        $path = $route->getPath();

        foreach ($methods as $method) {
            $this->prepareMethodRoutes($method);
            $this->routes[$method][$path] = $route;
        }
        return $this;
    }

    public function hasRoute(string $name): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->names, $name));
    }

    public function getRoute(string $name, $default = null): ?OwoSwingRouteInterface
    {
        return $this->names[$name] ?? $default;
    }

    public function removeRoute(string $name): self
    {
        if (true === OwoHelperArray::hasSetKey($this->names, $name)) {
            $this->removeMethodRoutes($this->names[$name]->getMethod(), $this->names[$name]->getPath());
            unset($this->names[$name]);
        }
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function removeMethodRoutes(string $method, string $path = null): self
    {
        if (true !== \is_null($path)) {
            foreach ($this->getMethodRoutes($method) as $route_path => $route) {
                if ($path === $route_path) {
                    unset($this->routes[$method][$route_path]);
                }
            }
        }
        else $this->routes[$method] = [];
        return $this;
    }

    public function getMethodRoutes(string $method): array
    {
        return $this->routes[$method] ?? [];
    }

    public function scope(string $scope, callable $callback, array $args = []): self
    {
        $current_scope = $this->scope ?? '/';
        $this->scope = \rtrim($current_scope, '/').'/'.\ltrim($scope, '/');
        $this->perform($callback, $args);
        $this->scope = $current_scope;
        return $this;
    }

    public function group(array $group, callable $callback, array $args = []): self
    {
        $current_group = $this->group ?? [];
        $this->group = \array_merge($current_group, $group);
        $this->perform($callback, $args);
        $this->group = $current_group;
        return $this;
    }

    public function perform(callable $callback, array $args = []): self
    {
        \call_user_func($callback, $this, $args);
        return $this;
    }

    protected function addNamedRoute(OwoSwingRouteInterface $route): self
    {
        if (true !== is_null($name = $route->getName())) $this->names[$name] = $route;
        return $this;
    }

    protected function prepareMethodRoutes(string $method): self
    {
        if (true !== \array_key_exists($method, $this->routes)) {
            $this->routes[$method] = [];
        }
        return $this;
    }

    protected function prepareRoute(OwoSwingRouteInterface $route): OwoSwingRouteInterface
    {
        $path = $route->getPath();
        if (true !== \is_null($this->scope)) {
            $path = \sprintf('%s/%s', \rtrim($this->scope, '/'), \ltrim($path, '/'));
        }

        if (true !== \is_null($this->prefix)) {
            $path = \sprintf('%s/%s', \rtrim($this->prefix, '/'), \ltrim($path, '/'));
        }
        $route->setPath($path);

        if (true !== empty($this->group)) {
            $route->action(\array_merge($this->group, [$route->getAction()]));
        }
        return $route;
    }

    protected static function createRoute(string $method, string $path, $action, string $name = null): OwoSwingRouteInterface
    {
        return new OwoSwingRoute($method, $path, $action, $name);
    }

    public static function createRouteFromParams(array $params): OwoSwingRouteInterface
    {
        foreach (['method', 'path', 'action'] as $key) {
            if (true !== OwoHelperArray::hasSetKey($params, $key)) {
                static::throwRuntimeException(\sprintf('Params Key [%s] Not Found', $key));
            }
        }

        list($method, $path, $action, $name) = [
            $params['method'], $params['path'], $params['action'], $params['name'] ?? null,
        ];
        return static::createRoute($method, $path, $action, $name);
    }
}
