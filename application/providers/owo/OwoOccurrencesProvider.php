<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Cores\OwoCoreProvider;
use framework\libraries\owo\classes\Cores\OwoCoreModel;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoOccurrencesProvider extends OwoCoreProvider
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
        return $this->provideOccurencess();
    }

    protected function provideOccurencess(): self
    {
        $this->provideModelOccurences();
        return $this;
    }

    protected function provideModelOccurences(): self
    {
        $dispatcher = $this->retrieve('Dispatcher::fromCores');
        OwoCoreModel::setDispatcher($dispatcher);
        return $this;
    }
}
