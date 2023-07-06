<?php

namespace framework\libraries\owo\classes\Swings;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Stackers\OwoStackerOnion;
use framework\libraries\owo\classes\Casters\OwoCasterInstantiator;
use framework\libraries\owo\classes\Servers\OwoServerResponse;

use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPathTrait;
use framework\libraries\owo\traits\Takes\Callables\OwoTakeCallableActionTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringMethodTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringNameTrait;


class OwoSwingRoute implements OwoSwingRouteInterface, OwoCoreMiddlewareInterface
{
    use OwoTakeStringPathTrait;
    use OwoTakeCallableActionTrait;
    use OwoTakeStringMethodTrait;
    use OwoTakeStringNameTrait;

    public const SWING_ROUTE_METHOD_DELIMITER = '|';

    public function __construct(string $method, string $path, $action, string $name = null)
    {
        $this->setMethod($method)->setPath($path)->action($action);
        if (true !== \is_null($name)) $this->setName($name);
    }

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $response = $this->carry($request);
        if (true === \is_null($response)) {
            return OwoServerResponse::ensureResponse($next($request));
        }
        return $response;
    }

    public function action($action): self
    {
        return $this->setAction(static::ensureCallableAction($action));
    }

    public function carry(OwoServerRequestInterface $request): ?OwoServerResponseInterface
    {
        if (true === $this->covers($request, $params)) {
            $response = \call_user_func($this->getHandler(), $request->map($this, $params));
            return OwoServerResponse::ensureResponse($response);
        }
        return null;
    }

    public function covers(OwoServerRequestInterface $request, array &$params = null): bool
    {
        if (true !== \is_null($path = $request->path())) {
            return (true === $this->links($request->getMethod(), $path, $params));
        }
        return false;
    }

    public function links(string $method, string $path, array &$params = null): bool
    {
        if (true === $this->accepts($method)) {
            return (true === $this->takes($path, $params));
        }
        return false;
    }

    public function takes(string $path, array &$params = null): bool
    {
        if (true === OwoHelperString::matches($this->pattern(), $path, $values)) {
            [ $params = [], \array_shift($values), ];
            if (\count($values) === \count($keys = $this->listParams())) {
                $params = \array_combine($keys, $values);
            }
            return true;
        }
        return false;
    }

    public function accepts(string $method): bool
    {
        $methods = \explode(static::SWING_ROUTE_METHOD_DELIMITER, $this->method);
        foreach (\explode(static::SWING_ROUTE_METHOD_DELIMITER, $method) as $value) {
            if (true === \in_array($value, $methods, true)) return true;
        }
        return false;
    }

    public function listParams(): array
    {
        return static::getPathParams($this->path);
    }

    public function getHandler(): callable
    {
        return OwoStackerOnion::ensureOnionHandler($this->action);
    }

    public function pattern(): string
    {
        return static::getPathPattern($this->path);
    }

    public function url(array $params = []): string
    {
        $url = $this->path;
        foreach ($params as $key => $value) {
            $url = \preg_replace(\sprintf('/\{%s\}/', $key), $value, $url);
        }
        return $url;
    }

    public function alikes(OwoSwingRouteInterface $route): bool
    {
        if ($this->path === $route->getPath()) {
            return (true === $this->accepts($route->getMethod()));
        }
        return false;
    }

    public static function ensureCallableAction($action): callable
    {
        if (true === \is_callable($action)) return $action;
        elseif (true === \is_array($action)) {
            $action = \array_map([static::class, 'ensureCallableAction'], $action);
            return OwoStackerOnion::ensureOnion($action);
        }
        elseif (true === \is_string($action)) {
            $action = OwoCasterInstantiator::newInstanceArgs($action);
            return static::ensureCallableAction($action);
        }
        return OwoStackerOnion::ensureOnion($action);
    }

    public static function getPathPattern(string $path): string
    {
        $pattern = \sprintf('/^\/?%s\/?$/', \str_replace('/', '\/', \trim($path, '/')));
        return \preg_replace('/(\{[^\/]+\})/', '([^\/]+)', $pattern);
    }

    public static function getPathParams(string $path): array
    {
        \preg_match_all('/\{([^\/]+)\}/', $path, $params);
        return $params[1] ?? [];
    }
}
