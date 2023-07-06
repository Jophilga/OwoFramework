<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Cores\OwoCoreProvider;
use framework\libraries\owo\classes\Cores\OwoCoreModel;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoCapsulatorsProvider extends OwoCoreProvider
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
        return $this->provideCapsulators();
    }

    protected function provideCapsulators(): self
    {
        $this->provideModelCapsulator();
        return $this;
    }

    protected function provideModelCapsulator(): self
    {
        $dbconnection = $this->retrieve('DBConnection::fromGlobals');
        OwoCoreModel::setDBConnection($dbconnection);
        return $this;
    }
}
