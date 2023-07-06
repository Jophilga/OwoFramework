<?php

namespace framework\libraries\owo\classes\Servers;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelperDecoder;
use framework\libraries\owo\classes\Helpers\OwoHelperUrl;

use framework\libraries\owo\interfaces\Servers\OwoServerRewriterInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyCallableMeasuresTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoServerRewriter implements OwoServerRewriterInterface
{
    use OwoTakeArrayKeyCallableMeasuresTrait;

    use OwoMakeCommonThrowerTrait;

    public function __construct(array $measures = [])
    {
        $this->setMeasures($measures);
    }

    public function rewriteUrlByQueryParam(string $url, string $param): self
    {
        if (true === \is_null($path = OwoHelperUrl::param($url, $param))) {
            $message = \sprintf('Url [%s] Query Param [%s] Not Found', $url, $param);
            static::throwRuntimeException($message);
        }
        return $this->rewrite($path);
    }

    public function rewriteUrlByPath(string $url): self
    {
        if (true === \is_null($path = OwoHelperUrl::path($url))) {
            static::throwRuntimeException(\sprintf('Url [%s] Path Not Found', $url));
        }
        return $this->rewrite($path);
    }

    public function getPathMeasure(string $path, array &$matches = null): ?callable
    {
        foreach ($this->measures as $pattern => $measure) {
            $pattern = OwoHelperString::pattern($pattern);
            if (true === OwoHelperString::matches($pattern, $path, $matches)) {
                return $measure;
            }
        }
        return null;
    }

    protected function rewrite(string $path): self
    {
        $path = OwoHelperDecoder::decodeUrl($path);
        if (true !== \is_null($measure = $this->getPathMeasure($path, $matches))) {
            \call_user_func($measure, $this, $matches ?? []);
        }
        return $this;
    }
}
