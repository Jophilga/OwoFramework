<?php

namespace application\middlewares\owo;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelperCoder;
use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Tuners\OwoTunerConfigurator;
use framework\libraries\owo\classes\Cores\OwoCoreMiddleware;

use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Tuners\OwoTunerConfiguratorInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoProgressiveWebAppMiddleware extends OwoCoreMiddleware
{
    use OwoMakeCommonThrowerTrait;

    protected $manifest = null;
    protected $head_code = '
    <meta name="description" content="{{description}}">
    <meta name="theme-color" content="{{theme_color}}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{name}}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-transparent">
    <meta name="msapplication-square310x310logo" content="{{icon}}">

    <link rel="apple-touch-icon" href="{{icon}}">
    <link rel="apple-touch-startup-image" href="{{icon}}">

    <link rel="manifest" href="{{manifest}}">
    ';
    protected $body_code = '
    <script>
        window.addEventListener("load", () => {
            if ("serviceWorker" in navigator) {
                try {
                    navigator.serviceWorker.register("{{sworker}}");
                    console.log("[SW] Registred ...");
                }
                catch (e) {
                    console.log("[SW] Not Registred ...");
                    console.log("[SW] Error: ", e);
                }
            }
        });
    </script>
    ';

    public function __invoke(OwoServerRequestInterface $request, callable $next): OwoServerResponseInterface
    {
        $response = $next($request);
        $response = OwoServerResponse::ensureResponse($response);

        if (true === $this->isPWAResponseWorkable($response)) {
            $response = $this->addPWAResponseContents($response);
        }
        return $response;
    }

    protected function loadManifestFromPublic(string $name = 'manifest.json'): ?OwoTunerConfiguratorInterface
    {
        return (new OwoTunerConfigurator())->loadJSON(OWO_ROOT.'/public/'.$name);
    }

    protected function renderPWAHeadCode(): string
    {
        $manifest = $this->ensureManifest();
        return \strtr($this->head_code, [
            '{{manifest}}' => '/manifest.json',
            '{{icon}}' => '/resources/statics/img/samples/sample.png',
            '{{description}}' => $manifest->search('description'),
            '{{theme_color}}' => $manifest->search('theme_color'),
            '{{name}}' => $manifest->search('name'),
        ]);
    }

    protected function renderPWABodyCode(): string
    {
        return \strtr($this->body_code, ['{{sworker}}' => '/sworker.js']);
    }

    protected function addPWAResponseContents(OwoServerResponseInterface $response): OwoServerResponseInterface
    {
        if (true === \is_string($html = $response->getBody())) {
            $html = OwoHelperCoder::injectOnFirstTagHtml($html, $this->renderPWAHeadCode(), '</head>');

            $body_code = OwoHelperString::minimize($this->renderPWABodyCode());
            $html = OwoHelperCoder::injectOnLastTagHtml($html, $body_code, '</body>');
            $response->setBody($html);
        }
        return $response;
    }

    protected function isPWAResponseWorkable(OwoServerResponseInterface $response): bool
    {
        if (true !== \is_null($body = $response->getBody())) {
            return (false !== \stripos($response->getHeader('Content-Type', ''), 'text/html'));
        }
        return false;
    }

    protected function ensureManifest(): OwoTunerConfiguratorInterface
    {
        $manifest = $this->manifest ?? $this->loadManifestFromPublic();
        if (true === \is_null($manifest)) {
            static::throwRuntimeException('Manifest Not Set Properly Or Not Found');
        }
        return $manifest;
    }
}
