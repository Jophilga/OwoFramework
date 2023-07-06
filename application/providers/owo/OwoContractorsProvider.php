<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Cores\OwoCoreService;
use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use application\services\owo\OwoCrudatorService;
use application\services\owo\OwoAuthenticatorService;
use application\services\owo\OwoRegistrarService;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoContractorsProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this;
    }

    public function provide(): self
    {
        $this->provideContractors();
        return $this;
    }

    public function provideContractors(): self
    {
        $this->provideServicesContractor();
        return $this;
    }

    public function provideServicesContractor(): self
    {
        OwoCoreService::setDIContainer($this->container);
        return $this->provideServices();
    }

    protected function provideServices(): self
    {
        $this->provideCrudatorService();
        $this->provideAuthenticatorService();
        $this->provideRegistrarService();
        return $this;
    }

    protected function provideCrudatorService(): self
    {
        OwoCrudatorService::setInstance($this->getCrudatorService());
        return $this;
    }

    protected function provideAuthenticatorService(): self
    {
        OwoAuthenticatorService::setInstance($this->getAuthenticatorService());
        return $this;
    }

    protected function provideRegistrarService(): self
    {
        OwoRegistrarService::setInstance($this->getRegistrarService());
        return $this;
    }

    protected function getCrudatorService(): OwoCrudatorService
    {
        $dbconnection = $this->retrieve('DBConnection::fromGlobals');
        return new OwoCrudatorService($dbconnection);
    }

    protected function getAuthenticatorService(): OwoAuthenticatorService
    {
        return new OwoAuthenticatorService();
    }

    protected function getRegistrarService(): OwoRegistrarService
    {
        return new OwoRegistrarService();
    }
}
