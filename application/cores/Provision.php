<?php

\defined('OWO_ROOT') or exit('No Script Access Allowed');


try {

    $dicontainer = \framework\libraries\owo\classes\Casters\OwoCasterDIContainer::getSingleton();

    $spcaterer = $dicontainer->get('SPCaterer::fromCores')->supplyProviders([
        'autoloader', 'preloader', 'capsulators', 'contractors', 'overlayers', 'occurences', 'services',
        'middlewares', 'displayer', 'rewriter',
    ]);

}
catch (\Throwable $e) {

    require OWO_ROOT.'/public/onerror.php';
    exit('<p class="catch">Something Happened While Providing Application.</p>');

}
