<?php

\defined('OWO_ROOT') or exit('No Script Access Allowed');


use framework\libraries\owo\classes\Casters\OwoCasterDIContainer;
use framework\libraries\owo\classes\Tuners\OwoTunerConfigenv;


try {

    $dicontainer = OwoCasterDIContainer::getSingleton();
    $configenv = $dicontainer->get('Configenv::fromCores')->publish();

    if (true !== \defined('OWO_ENVIRONMENT')) {
        \define('OWO_ENVIRONMENT', OwoTunerConfigenv::getEnv('OWO_APP_ENV', 'production'));
    }

    $configini = $dicontainer->get('Configini::fromCores');
    switch (OWO_ENVIRONMENT)
    {
        case 'preproduction':
        case 'production':
            $configini->addConfig('error_reporting', \strval(\E_ALL & ~\E_NOTICE));
            $configini->addConfig('display_errors', '0');
        break;

        case 'development':
            $configini->addConfig('error_reporting', \strval(\E_ALL));
            $configini->addConfig('display_errors', '1');
        break;

        default:
            \header('HTTP/1.1 503 Service Unavailable.', true, 503);
            $message = \sprintf('Unknown Environment [%s]', OWO_ENVIRONMENT);
            throw new \RuntimeException($message);
        break;
    }

    $json = OWO_ROOT.'/application/cores/configurations/locales/config.'.OWO_ENVIRONMENT.'.json';
    $configurator = $dicontainer->get('Configurator::fromCores')->loadJSON($json);

    $configini->addConfigs($configurator->search('configs.ini', []))->publish();

}
catch (\Throwable $e) {

    require OWO_ROOT.'/public/onerror.php';
    exit('<p class="catch">Something Happened While Preparing Application.</p>');

}
