<?php

namespace framework\libraries\owo\classes\Servers;

use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Stackers\OwoStackerOnionInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerInterface;

use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerPortTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeConfigsTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringProtocolTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringHostTrait;


class OwoServer implements OwoServerInterface, OwoCoreMiddlewareInterface
{
    use OwoTakeStringHostTrait;
    use OwoTakeArrayKeyMixeConfigsTrait;
    use OwoTakeStringProtocolTrait;
    use OwoTakeIntegerPortTrait;

    public const SERVER_DEFAULT_PROTOCOL = 'http';

    public const SERVER_DEFAULT_PORT = 80;

    public const SERVER_DEFAULT_HOST = 'localhost';

    protected $stacker = null;

    public function __construct(OwoStackerOnionInterface $stacker, array $configs = [])
    {
        $this->setProtocol(static::SERVER_DEFAULT_PROTOCOL);
        $this->listen(static::SERVER_DEFAULT_HOST, static::SERVER_DEFAULT_PORT);
        $this->setStacker($stacker)->setConfigs($configs);
    }

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        return $this->handle($request);
    }

    public function setStacker(OwoStackerOnionInterface $stacker): self
    {
        $this->stacker = $stacker;
        return $this;
    }

    public function getStacker(): ?OwoStackerOnionInterface
    {
        return $this->stacker;
    }

    public function middlewares(array $middlewares): self
    {
        foreach ($middlewares as $middleware) $this->middleware($middleware);
        return $this;
    }

    public function middleware(callable $middleware): self
    {
        $this->stacker->addStackable($middleware);
        return $this;
    }

    public function handle(OwoServerRequestInterface $request): OwoServerResponseInterface
    {
        if (true === $this->serves($request)) {
            $response = $this->stacker->process($request);
            return OwoServerResponse::ensureResponse($response);
        }
        return new OwoServerResponse('Bad Request', 400);
    }

    public function serves(OwoServerRequestInterface $request): bool
    {
        list($protocol, $host, $port) = [
            $request->scheme(static::SERVER_DEFAULT_PROTOCOL),
            $request->host(static::SERVER_DEFAULT_HOST),
            $request->port(static::SERVER_DEFAULT_PORT),
        ];

        if (OwoHelperString::lowerCase($protocol) === OwoHelperString::lowerCase($this->protocol)) {
            if (OwoHelperString::lowerCase($host) === OwoHelperString::lowerCase($this->host)) {
                return ($port === $this->port);
            }
        }
        return false;
    }

    public function listen(string $host, int $port = 80): self
    {
        return $this->setHost($host)->setPort($port);
    }

    public function setHostUsingConfigs($default = 'localhost'): self
    {
        return $this->setHost($this->getHostUsingConfigs($default));
    }

    public function getHostUsingConfigs($default = 'localhost'): string
    {
        $matches = \explode(':', \strval($this->getConfig('HTTP_HOST', $default)));
        return $matches[0];
    }

    public function setPortUsingConfigs($default = 80): self
    {
        return $this->setPort($this->getPortUsingConfigs($default));
    }

    public function getPortUsingConfigs($default = 80): int
    {
        return \intval($this->getConfig('SERVER_PORT', $default));
    }

    public function hasHttpsOnUsingConfigs(): bool
    {
        return ('on' === OwoHelperString::lowerCase($this->getConfig('HTTPS', 'off')));
    }

    public function getClientHostUsingConfigs($default = null): string
    {
        $matches = \explode(':', \strval($this->getConfig('REMOTE_ADDR', $default)));
        return $matches[0];
    }

    public function getClientPortUsingConfigs($default = null): int
    {
        return \intval($this->getConfig('REMOTE_PORT', $default));
    }
}
