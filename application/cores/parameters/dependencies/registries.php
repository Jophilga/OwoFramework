<?php

\defined('OWO_ROOT') or exit('No direct script access allowed');


use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


return [

    'Assetory::fromResources' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $dir = OWO_ROOT.'/application/resources/assets';
        $assetor = new \framework\libraries\owo\classes\Drives\OwoDriveAssetory($dir);
        return $assetor;
    },

    'BinderPDO::fromGlobals' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $params = [
            'driver' => \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_DATABASE_DRIVER', 'mysql'),
            'dbname' => \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_DATABASE_DBNAME', 'database'),
            'user' => \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_DATABASE_USERNAME', 'root'),
            'pass' => \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_DATABASE_PASSWORD', 'root'),
            'host' => \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_DATABASE_HOST', 'localhost'),
            'port' => \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_DATABASE_PORT', 3306),
        ];
        $binder = new \framework\libraries\owo\classes\Binders\OwoBinderPDO($params, []);
        return $binder;
    },

    'Configenv::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $filedot = OWO_ROOT.'/application/cores/configurations/globals/config.application.env';
        $configenv = new \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv([], []);
        return $configenv->loadDOT($filedot);
    },

    'Configini::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $filedot = OWO_ROOT.'/application/cores/configurations/globals/config.application.ini';
        $configini = new \framework\libraries\owo\classes\Tuners\OwoTunerConfigini([], []);
        return $configini->loadDOT($filedot);
    },

    'Configurator::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $filejson = OWO_ROOT.'/application/cores/configurations/globals/config.application.json';
        $configurator = new \framework\libraries\owo\classes\Tuners\OwoTunerConfigurator([], []);
        return $configurator->loadJSON($filejson);
    },

    'DBConnection::fromGlobals' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $executor = $dicontainer->get('Executor::fromGlobals');
        $dbconnection = new \framework\libraries\owo\classes\Dbases\OwoDbaseConnectionSql($executor, []);
        return $dbconnection;
    },

    'Dictionary::fromResources' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $dir = OWO_ROOT.'/application/resources/statics/terms';
        $dictionary = new \framework\libraries\owo\classes\Varies\OwoVaryDictionary('en', []);
        return $dictionary->setLangFromGlobals()->loadTermsFromLangJSON($dir);
    },

    'Dispatcher::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $observers = require OWO_ROOT.'/application/cores/parameters/occurrences/observers.php';
        $emitter = new \framework\libraries\owo\classes\Events\OwoEventDispatcher($observers);
        return $emitter;
    },

    'Executor::fromGlobals' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $binder = $dicontainer->get('BinderPDO::fromGlobals')->connect();
        $executor = new \framework\libraries\owo\classes\Queries\OwoQueryExecutor($binder->getPDO(), []);
        return $executor;
    },

    'Inspector::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $permissions = require OWO_ROOT.'/application/cores/parameters/permissions.php';
        $inspector = new \framework\libraries\owo\classes\Guards\OwoGuardInspector($permissions);
        return $inspector;
    },

    'Journal::fromTemporay' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $path = OWO_ROOT.'/temporary/journals/defaults/errors.log';
        $journal = new \framework\libraries\owo\classes\Drives\OwoDriveJournal($path);
        return $journal;
    },

    'Keypair::fromResources' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $dir = OWO_ROOT.'/application/resources/statics/secrets';
        $public_key = \framework\libraries\owo\classes\Helpers\OwoHelperBackrest::loadContents($dir.'/public.key');
        $private_key = \framework\libraries\owo\classes\Helpers\OwoHelperBackrest::loadContents($dir.'/private.key');
        $keypair = new \framework\libraries\owo\classes\Guards\OwoGuardKeypair($private_key, $public_key);
        return $keypair;
    },

    'Request::fromGlobals' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $url = \framework\libraries\owo\classes\Helpers\OwoHelperCapturer::captureWholeHttpServerRequestUrl();
        $url = \framework\libraries\owo\classes\Helpers\OwoHelperDecoder::decodeUrl($url);

        $method = \framework\libraries\owo\classes\Helpers\OwoHelperCapturer::captureServerRequestMethod('GET');
        $headers = \framework\libraries\owo\classes\Helpers\OwoHelperHeader::getCurrentRequestHeaders();

        $uploads = \framework\libraries\owo\classes\Helpers\OwoHelperUpload::getUploadsFromGlobals();
        $cookies = \framework\libraries\owo\classes\Helpers\OwoHelperCookier::getCookiesFromGlobals();
        $request = new \framework\libraries\owo\classes\Servers\OwoServerRequest($url, $method, $headers);
        return $request->addUploads($uploads)->addCookies($cookies);
    },

    'Rewriter::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $measures = require OWO_ROOT.'/application/cores/parameters/measures.php';
        $rewriter = new \framework\libraries\owo\classes\Servers\OwoServerRewriter($measures);
        return $rewriter;
    },

    'Router::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $filejson = OWO_ROOT.'/application/cores/configurations/routes.json';
        $router = new \framework\libraries\owo\classes\Swings\OwoSwingRouter([], '/');
        $router->mapRoutesFromJSON($filejson);

        return $router->api(function (\framework\libraries\owo\interfaces\Swings\OwoSwingRouterInterface $router) {
            require OWO_ROOT.'/application/cores/factories/routes/api.php';
        })->web(function (\framework\libraries\owo\interfaces\Swings\OwoSwingRouterInterface $router) {
            require OWO_ROOT.'/application/cores/factories/routes/web.php';
        });
    },

    'Server::fromGlobals' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $stacker = $dicontainer->get('Stacker::fromCores');
        $server = new \framework\libraries\owo\classes\Servers\OwoServer($stacker, $_SERVER);
        $server->middleware($dicontainer->get('Router::fromCores'));

        list($host, $port) = [
            \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_APP_HOST', 'localhost'),
            \framework\libraries\owo\classes\Tuners\OwoTunerConfigenv::getEnv('OWO_APP_PORT', 80),
        ];
        return $server->listen($host, $port);
    },

    'SPCaterer::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $providers = require OWO_ROOT.'/application/cores/parameters/providers.php';
        $spcaterer = new \framework\libraries\owo\classes\Casters\OwoCasterSPCaterer($dicontainer, $providers);
        return $spcaterer;
    },

    'Stacker::fromCores' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $stackables = require OWO_ROOT.'/application/cores/parameters/stackables.php';
        $stacker = new \framework\libraries\owo\classes\Stackers\OwoStackerOnion($stackables);
        return $stacker;
    },

    'Templator::fromTemporay' => function (OwoCasterDIContainerInterface $dicontainer, array $args = [])
    {
        $directory = OWO_ROOT.'/temporary/caches/templates';
        $cachedir = new \framework\libraries\owo\classes\Caches\OwoCacheDirectory($directory, 86400, true);
        $templator = new \framework\libraries\owo\classes\Codes\OwoCodeTemplatorPHP($cachedir, true);
        return $templator;
    },

];
