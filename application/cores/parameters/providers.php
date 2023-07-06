<?php

\defined('OWO_ROOT') or exit('No direct script access allowed');


return [

    'autoloader' => \application\providers\owo\OwoAutoloaderProvider::class,
    'capsulators' => \application\providers\owo\OwoCapsulatorsProvider::class,
    'contractors' => \application\providers\owo\OwoContractorsProvider::class,
    'displayer' => \application\providers\owo\OwoDisplayerProvider::class,
    'middlewares' => \application\providers\owo\OwoAppMiddlewaresProvider::class,
    'occurences' => \application\providers\owo\OwoOccurrencesProvider::class,
    'overlayers' => \application\providers\owo\OwoOverlayersProvider::class,
    'preloader' => \application\providers\owo\OwoPreloaderProvider::class,
    'rewriter' => \application\providers\owo\OwoRewriterProvider::class,
    'services' => \application\providers\owo\OwoAppServicesProvider::class,

];
