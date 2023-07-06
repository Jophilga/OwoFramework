<?php

if (true !== \defined('OWO_ROOT')) {
    \define('OWO_ROOT', \dirname(__DIR__)) or exit('No Script Access Allowed');
}


require_once OWO_ROOT.'/application/cores/Bootstrap.php';
require_once OWO_ROOT.'/application/cores/Environment.php';
require_once OWO_ROOT.'/application/cores/Provision.php';


try {

    $application = \framework\libraries\owo\classes\Casters\OwoCasterDIContainer::getSingleton();
    $response = $application->get('Server::fromGlobals')->handle($application->get('Request::fromGlobals'));

    $application->get('Displayer::getSingleton')->cleanThenDisplayOnOutput($response);

}
catch (\Throwable $e) {

    require OWO_ROOT.'/public/onerror.php';
    exit('<p class="catch">Something Happened While Running Application.</p>');

}
