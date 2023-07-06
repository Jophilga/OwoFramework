<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Varies\OwoVaryDisplayer;
use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoDisplayerProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this->registerDisplayerService();
    }

    public function provide(): self
    {
        return $this->provideDisplayerService();
    }

    protected function registerDisplayerService(): self
    {
        return $this->define('Displayer::getSingleton', function () {
            return new OwoVaryDisplayer();
        });
    }

    protected function provideDisplayerService(): self
    {
        $displayer = $this->retrieve('Displayer::getSingleton');
        $displayer->registerObEndFlushAsShutdown();
        return $this;
    }
}
