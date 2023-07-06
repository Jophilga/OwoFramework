<?php

namespace framework\libraries\owo\interfaces\Cores;

use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


interface OwoCoreMiddlewareInterface
{
    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface;
}
