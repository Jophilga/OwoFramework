<?php

namespace application\views\owo;

use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Cores\OwoCoreView;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;


class OwoMaintenanceView extends OwoCoreView implements OwoCoreMiddlewareInterface
{
    public const MAINTENANCE_VIEW_TEMPLATE = 'common/sample.html.php';

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $data = $this->addAttributes($request->getAttributes())->getAttributes();
        return static::getMaintenanceResponse($data);
    }

    public static function getMaintenanceResponse(array $data = []): OwoServerResponseInterface
    {
        $data['title'] = $data['title'] ?? 'Whoops... Page Under Maintenance';
        $data['message'] = $data['message'] ?? 'We could not found what you are looking for :(';
        $data['lang'] = $data['lang'] ?? 'en';

        $template = static::getViewPath(static::MAINTENANCE_VIEW_TEMPLATE);
        $body = static::getTemplator()->render($template, $data, false);
        return OwoServerResponse::html($body, 200);
    }
}
