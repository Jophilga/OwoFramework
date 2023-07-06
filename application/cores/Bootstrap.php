<?php

\defined('OWO_ROOT') or exit('No Script Access Allowed');


require_once OWO_ROOT.'/framework/cores/Autoload.php';
require_once OWO_ROOT.'/framework/cores/Rewrite.php';


use framework\libraries\owo\classes\Casters\OwoCasterDIContainer;


try {

    $registries = require OWO_ROOT.'/application/cores/parameters/dependencies/registries.php';
    $summons = require OWO_ROOT.'/application/cores/parameters/dependencies/summons.php';
    $procedures = require OWO_ROOT.'/application/cores/parameters/dependencies/procedures.php';

    $dicontainer = new OwoCasterDIContainer($registries, $procedures, $summons);
    OwoCasterDIContainer::setSingleton($dicontainer);

}
catch (\Throwable $e) {

    require OWO_ROOT.'/public/onerror.php';
    exit('<p class="catch">Something Happened While Bootstraping Application.</p>');

}
