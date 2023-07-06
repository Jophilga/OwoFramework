<?php

namespace application\providers\owo;

use framework\libraries\owo\classes\Tuners\OwoTunerConfigenv;
use framework\libraries\owo\classes\Helpers\OwoHelperCapturer;
use framework\libraries\owo\classes\Cores\OwoCoreProvider;
use framework\libraries\owo\classes\Helpers\OwoHelperDecoder;

use framework\libraries\owo\interfaces\Casters\OwoCasterDIContainerInterface;


class OwoRewriterProvider extends OwoCoreProvider
{
    public function __construct(OwoCasterDIContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function register(): self
    {
        return $this;
    }

    public function provide(): self
    {
        return $this->provideRewriterService();
    }

    protected function provideRewriterService(): self
    {
        if ('on' === OwoTunerConfigenv::getEnv('OWO_APP_REWRITE', 'off')) {
            $rewriter = $this->retrieve('Rewriter::fromCores');
            $rewriter->rewriteUrlByQueryParam($this->getDecodedCapturedUrl(), 'url');
        }
        return $this;
    }

    protected function getDecodedCapturedUrl(): string
    {
        $url = OwoHelperCapturer::captureWholeHttpServerRequestUrl();
        return OwoHelperDecoder::decodeUrl($url);
    }
}
