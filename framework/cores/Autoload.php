<?php

\defined('OWO_ROOT') or exit('No Script Access Allowed');


if (true !== \defined('OWO_COMPOSER_AUTOLOAD_FILEPATH')) {
    \define('OWO_COMPOSER_AUTOLOAD_FILEPATH', OWO_ROOT.'/vendor/autoload.php');
}


if (true !== \function_exists('owo_autoload')) {
    function owo_autoload(string $classname)
    {
        if (true !== \file_exists($path = owo_get_class_path($classname))) {
            throw new \RuntimeException(\sprintf('Class [%s] Not Found', $classname));
        }
        include_once $path;
    }
}

if (true !== \function_exists('owo_get_class_path')) {
    function owo_get_class_path(string $class, string $dir = null): string
    {
        if (true !== \boolval(\preg_match('/\.php$/', $class))) $class .= ('.php');
        return ($dir ?? OWO_ROOT).'/'.\str_replace('\\', '/', $class);
    }
}


try {

    if (true === \file_exists(OWO_COMPOSER_AUTOLOAD_FILEPATH)) {
        require_once OWO_COMPOSER_AUTOLOAD_FILEPATH;
    }

    if (true !== \spl_autoload_register('owo_autoload', true, true)) {
        throw new \RuntimeException('Autoload Register Failed');
    }

}
catch (\Throwable $e) {

    require OWO_ROOT.'/public/onerror.php';
    exit('Something Happened While Autoloading Application.');

}
