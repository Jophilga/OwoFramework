<?php

\defined('OWO_ROOT') or exit('No Script Access Allowed');


if (true !== \function_exists('owo_exists_uri')) {
    function owo_exists_uri(string $uri, string $root = OWO_ROOT): bool
    {
        $path = \rtrim($root, '/').'/'.\ltrim($uri, '/');
        if (('/' !== $uri) && (true === \file_exists($path))) {
            return true;
        }
        return false;
    }
}


try {

    $uri = \urldecode(\parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH));
    if (true === owo_exists_uri($uri, OWO_ROOT.'/public')) {
        return false;
    }

}
catch (\Throwable $e) {

    require OWO_ROOT.'/public/onerror.php';
    exit('Something Happened While Uri Rewriting Application.');

}
