<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoOverlayersProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        // TODO:
        return $this;
    }

    public function provide(): self
    {
        // TODO:
        return $this;
    }
}
