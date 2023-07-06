<?php

namespace application\views\owo;

use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Cores\OwoCoreView;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;


class OwoHomeView extends OwoCoreView implements OwoCoreMiddlewareInterface
{
    public const MAINTENANCE_VIEW_TEMPLATE = 'common/sample.html.php';

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $data = $this->addAttributes($request->getAttributes())->getAttributes();
        return static::getHomeResponse($data);
    }

    public static function getHomeResponse(array $data = []): OwoServerResponseInterface
    {
        $data['title'] = $data['title'] ?? 'Welcome world!';
        $data['message'] = $data['message'] ?? 'Congratulations! You have arrived on the landing page :-D';
        $data['lang'] = $data['lang'] ?? 'en';

        $template = static::getViewPath(static::MAINTENANCE_VIEW_TEMPLATE);
        $body = static::getTemplator()->render($template, $data, false);
        return OwoServerResponse::html($body, 200);
    }
}
