<?php

namespace application\controllers\owo;

use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Cores\OwoCoreController;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;


class OwoNotFoundController extends OwoCoreController implements OwoCoreMiddlewareInterface
{
    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $response = $next($request);
        $response = OwoServerResponse::ensureResponse($response);
        return $response;
    }
}
