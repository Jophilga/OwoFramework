<?php

\defined('OWO_ROOT') or exit('No direct script access allowed');


use framework\libraries\owo\interfaces\Servers\OwoServerRewriterInterface;


return [

    '/^\/?img\/(?<resource>.+)/' => function (OwoServerRewriterInterface $rewriter, array $matches = [])
    {
        $dir = OWO_ROOT.'/public/resources/statics/img';
        if (true === \file_exists($file = $dir.'/'.$matches['resource'])) {
            $rewriter->read($file);
            exit();
        }
    },

    '/^\/?html\/(?<resource>.+)/' => function (OwoServerRewriterInterface $rewriter, array $matches = [])
    {
        $dir = OWO_ROOT.'/public/resources/assets/html';
        if (true === \file_exists($file = $dir.'/'.$matches['resource'])) {
            $rewriter->read($file);
            exit();
        }
    },

    '/^\/?js\/(?<resource>.+)/' => function (OwoServerRewriterInterface $rewriter, array $matches = [])
    {
        $dir = OWO_ROOT.'/public/resources/assets/js';
        if (true === \file_exists($file = $dir.'/'.$matches['resource'])) {
            $rewriter->read($file);
            exit();
        }
    },

    '/^\/?css\/(?<resource>.+)/' => function (OwoServerRewriterInterface $rewriter, array $matches = [])
    {
        $dir = OWO_ROOT.'/public/resources/assets/css';
        if (true === \file_exists($file = $dir.'/'.$matches['resource'])) {
            $rewriter->read($file);
            exit();
        }
    },

];
