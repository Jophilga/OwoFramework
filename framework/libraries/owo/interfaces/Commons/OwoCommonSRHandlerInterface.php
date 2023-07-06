<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


interface OwoCommonSRHandlerInterface
{
    public function handle(OwoServerRequestInterface $request): OwoServerResponseInterface;
}
