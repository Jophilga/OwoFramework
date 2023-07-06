<?php

namespace framework\libraries\owo\classes\Servers;

use framework\libraries\owo\classes\Helpers\OwoHelperHeader;
use framework\libraries\owo\classes\Helpers\OwoHelperCookier;
use framework\libraries\owo\classes\Servers\OwoServerStatus;

use framework\libraries\owo\interfaces\Servers\OwoServerStatusInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Varies\OwoVaryDisplayerInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeHeadersTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeParamsTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeCookiesTrait;
use framework\libraries\owo\traits\Takes\Mixes\OwoTakeMixeBodyTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonAttributorTrait;


class OwoServerResponse implements OwoServerResponseInterface
{
    use OwoMakeCommonAttributorTrait;

    use OwoTakeArrayKeyMixeHeadersTrait;
    use OwoTakeArrayKeyMixeParamsTrait;
    use OwoTakeArrayKeyMixeCookiesTrait;
    use OwoTakeMixeBodyTrait;

    protected $status = null;

    public function __construct($body = null, int $code = 200, array $headers = [])
    {
        $this->setBody($body)->setStatusWithCode($code)->setHeaders($headers);
    }

    public function setStatus(OwoServerStatusInterface $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): ?OwoServerStatusInterface
    {
        return $this->status;
    }

    public function setStatusWithCode(int $code): self
    {
        return $this->setStatus(new OwoServerStatus($code));
    }

    public function display(OwoVaryDisplayerInterface $displayer): self
    {
        $this->publishStatusCode()->sendHeaders()->sendCookies();
        $displayer->displayOnOutput($this->body);
        return $this;
    }

    public function getStatusMessage(): string
    {
        return $this->status->getMessage();
    }

    public function getStatusCode(): int
    {
        return $this->status->getCode();
    }

    public function publishStatusCode(): self
    {
        static::sendStatusCode($this->getStatusCode());
        return $this;
    }

    public function sendCookies(): self
    {
        OwoHelperCookier::addCookies($this->cookies, $this->params);
        return $this;
    }

    public function sendHeaders(): self
    {
        OwoHelperHeader::publishHeaders($this->headers, true);
        return $this;
    }

    public function addXmlHeaders(): self
    {
        return $this->addHeader('Content-Type', 'application/xml;charset=utf-8');
    }

    public function addJsonHeaders(): self
    {
        return $this->addHeader('Content-Type', 'application/json;charset=utf-8');
    }

    public function addCssHeaders(): self
    {
        return $this->addHeader('Content-Type', 'text/css;charset=utf-8');
    }

    public function addJsHeaders(): self
    {
        return $this->addHeader('Content-Type', 'application/javascript;charset=utf-8');
    }

    public function addHtmlHeaders(): self
    {
        return $this->addHeader('Content-Type', 'text/html;charset=utf-8');
    }

    public static function sendStatusCode(int $code)
    {
        \http_response_code($code);
    }

    public static function getSentStatusCode($default = null): ?int
    {
        if (false === ($code = \http_response_code())) return $default;
        return $code;
    }

    public static function xml($body = null, int $code = 200, array $headers = []): self
    {
        $response = new static($body, $code, $headers);
        return $response->addXmlHeaders();
    }

    public static function html($body = null, int $code = 200, array $headers = []): self
    {
        $response = new static($body, $code, $headers);
        return $response->addHtmlHeaders();
    }

    public static function json($body = null, int $code = 200, array $headers = []): self
    {
        $response = new static($body, $code, $headers);
        return $response->addJsonHeaders();
    }

    public static function js($body = null, int $code = 200, array $headers = []): self
    {
        $response = new static($body, $code, $headers);
        return $response->addJsHeaders();
    }

    public static function css($body = null, int $code = 200, array $headers = []): self
    {
        $response = new static($body, $code, $headers);
        return $response->addCssHeaders();
    }

    public static function ensureResponse($response): OwoServerResponseInterface
    {
        if (true === \is_subclass_of($response, OwoServerResponseInterface::class)) {
            return $response;
        }
        return new static($response);
    }
}
