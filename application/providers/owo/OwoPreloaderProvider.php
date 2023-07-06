<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Seekers\OwoSeekerPreloader;
use framework\libraries\owo\classes\Cores\OwoCoreProvider;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoPreloaderProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this->registerPreloaderService();
    }

    public function provide(): self
    {
        return $this->providePreloaderService();
    }

    protected function registerPreloaderService(): self
    {
        return $this->define('Preloader::getSingleton', function ()
        {
            $ignores = [ OWO_ROOT.'/public', OWO_ROOT.'/temporary', ];
            $preloader = new OwoSeekerPreloader(OwoSeekerPreloader::SEEK_PRELOADER_PATTERN_PHP, $ignores);
            return $preloader;
        });
    }

    protected function providePreloaderService(): self
    {
        $preloader = $this->retrieve('Preloader::getSingleton');
        $libraries = [
            OWO_ROOT.'/framework/libraries/', OWO_ROOT.'/application/libraries/',
        ];
        $preloader->preloadPaths($libraries);
        return $this;
    }
}
