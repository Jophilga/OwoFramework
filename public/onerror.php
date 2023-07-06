<?php

if (true !== \defined('OWO_ROOT')) {
    \define('OWO_ROOT', \dirname(__DIR__)) or exit('No Script Access Allowed');
}


if (true !== \defined('OWO_JOURNAL_FILEPATH')) {
    \define('OWO_JOURNAL_FILEPATH', OWO_ROOT.'/temporary/journals/defaults/errors.log');
}

if (true !== \defined('OWO_ERROR_404_FILEPATH')) {
    \define('OWO_ERROR_404_FILEPATH', OWO_ROOT.'/public/error404.php');
}


if (true !== \function_exists('owo_error_log')) {
    function owo_error_log(string $message, string $file): bool
    {
        return (true === \error_log($message.\PHP_EOL, 3, $file));
    }
}

if (true !== \function_exists('owo_getenv')) {
    function owo_getenv(string $name, $default = null)
    {
        return \getenv($name, true) ?: \getenv($name) ?: $default;
    }
}


try {

    if (true === isset($e)) {
        $_SERVER['REDIRECT_STATUS'] = 500;
        $message = \sprintf('[%s] [ERROR] %s <br />', \date(\DATE_COOKIE), \strval($e));
        owo_error_log($message, OWO_JOURNAL_FILEPATH);

        if ('development' === owo_getenv('OWO_APP_ENV', 'preproduction')) {
            if ('on' === owo_getenv('OWO_APP_DEBUG', 'off')) {
                $format = ('<textarea rows="12" cols="30" readonly>%s</textarea>');
                $data['error_debug'] = \sprintf($format, $message);
            }
        }
    }

    if (true !== isset($data['error_title'])) {
        $_SERVER['REDIRECT_STATUS'] = $_SERVER['REDIRECT_STATUS'] ?? 404;
        $status = (404 === $_SERVER['REDIRECT_STATUS']) ? ('Not Found') : ('Issue Occurred');
        $data['error_title'] = \sprintf('Whoops... %d - %s', $_SERVER['REDIRECT_STATUS'], $status);
    }

    $data['error_title'] = \htmlentities($data['error_title'], \ENT_QUOTES, 'UTF-8');
    require OWO_ERROR_404_FILEPATH;

}
catch (\Throwable $e) {

    owo_error_log(\strval($e), OWO_JOURNAL_FILEPATH);
    exit('<p class="catch">Unhandled Error Occurred While Running Application.</p>');

}
