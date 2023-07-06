<?php

namespace framework\libraries\owo\interfaces\Commons;

use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


interface OwoCommonSRSenderInterface
{
    public function send(OwoServerRequestInterface $request, array $options = []): ?OwoServerResponseInterface;
}
