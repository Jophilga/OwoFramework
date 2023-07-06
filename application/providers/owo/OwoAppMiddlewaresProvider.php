<?php

namespace application\providers\owo;

use application\middlewares\owo\OwoSiteUnderMaintenanceMiddleware;

use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoAppMiddlewaresProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this->registerMiddlewareServices();
    }

    public function provide(): self
    {
        return $this->provideMiddlewareServices();
    }

    protected function registerMiddlewareServices(): self
    {
        return $this->defineMiddlewares($this->listMiddlewares());
    }

    protected function provideMiddlewareServices(): self
    {
        $this->container->addSummon('middleware', $this->getMiddlewareResolver());
        return $this;
    }

    protected function getMiddlewareResolver(): callable
    {
        return function (OwoCasterDIContainerInterface $dicontainer, $middleware): callable {
            return $dicontainer->instantiate($middleware);
        };
    }

    protected function defineMiddlewares(array $middlewares): self
    {
        foreach ($this->listMiddlewares() as $name => $middleware) {
            $this->define($name, function () use ($middleware): object {
                return $middleware;
            });
        }
        return $this;
    }

    protected function listMiddlewares(): array
    {
        return [
            'maintenance' => new OwoSiteUnderMaintenanceMiddleware(),
        ];
    }
}
