<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreServiceInterface;


class OwoAppServicesProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this->registerServiceServices();
    }

    public function provide(): self
    {
        return $this->provideServiceServices();
    }

    protected function registerServiceServices(): self
    {
        foreach ($this->listServices() as $name => $service) {
            $this->define($service, $name);
        }
        return $this;
    }

    protected function provideServiceServices(): self
    {
        $this->container->addSummon('service', $this->getServiceResolver());
        return $this;
    }

    protected function getServiceResolver(): callable
    {
        return function (OwoCasterDIContainerInterface $dicontainer, $service)
        : OwoCoreServiceInterface {
            return $dicontainer->instantiate($service);
        };
    }

    protected function listServices(): array
    {
        return [
            // TODO:
        ];
    }
}
