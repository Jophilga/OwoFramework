<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Seekers\OwoSeekerAutoloader;
use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoAutoloaderProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this->registerAutoloaderService();
    }

    public function provide(): self
    {
        return $this->provideAutoloaderService();
    }

    protected function registerAutoloaderService(): self
    {
        return $this->define('Autoloader::getSingleton', function ()
        {
            $dirs = [ OWO_ROOT, OWO_ROOT.'/thirdparty', ];
            $autoloader = new OwoSeekerAutoloader($dirs, false);
            return $autoloader;
        });
    }

    protected function provideAutoloaderService(): self
    {
        $autoloader = $this->retrieve('Autoloader::getSingleton');
        $autoloader->addIncludePath(OWO_ROOT)->register(true);
        return $this;
    }
}
