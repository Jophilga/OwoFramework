<?php

namespace application\views\owo;

use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Cores\OwoCoreView;

use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreMiddlewareInterface;


class OwoNotFoundView extends OwoCoreView implements OwoCoreMiddlewareInterface
{
    public const NOT_FOUND_VIEW_TEMPLATE = 'common/sample.html.php';

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $data = $this->addAttributes($request->getAttributes())->getAttributes();
        return static::getNotFoundResponse($data);
    }

    public static function getNotFoundResponse(array $data = []): OwoServerResponseInterface
    {
        $data['title'] = $data['title'] ?? 'Whoops... Page Not Found';
        $data['message'] = $data['message'] ?? 'We could not found what you are looking for :(';
        $data['lang'] = $data['lang'] ?? 'en';

        $template = static::getViewPath(static::NOT_FOUND_VIEW_TEMPLATE);
        $body = static::getTemplator()->render($template, $data, false);
        return OwoServerResponse::html($body, 200);
    }
}
