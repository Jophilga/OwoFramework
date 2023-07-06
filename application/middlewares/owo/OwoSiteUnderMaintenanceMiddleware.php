<?php

namespace application\middlewares\owo;

use framework\libraries\owo\classes\Tuners\OwoTunerConfigenv;
use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Cores\OwoCoreMiddleware;

use application\views\owo\OwoMaintenanceView;

use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


class OwoSiteUnderMaintenanceMiddleware extends OwoCoreMiddleware
{
    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        if (true === $this->checkIfSiteUnderMaintenance()) {
            return OwoMaintenanceView::getMaintenanceResponse();
        }

        $response = $next($request);
        $response = OwoServerResponse::ensureResponse($response);
        return $response;
    }

    protected function checkIfSiteUnderMaintenance(): bool
    {
        $on = OwoTunerConfigenv::getEnv('OWO_APP_MAINTENANCE', 'off');
        return ('on' === $on);
    }
}
