<?php

namespace framework\libraries\owo\interfaces\Servers;

use framework\libraries\owo\interfaces\Commons\OwoCommonAttributorInterface;
use framework\libraries\owo\interfaces\Commons\OwoCommonDisplayableInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerStatusInterface;


interface OwoServerResponseInterface extends OwoCommonAttributorInterface, OwoCommonDisplayableInterface
{
    public function setStatusWithCode(int $code): self;

    public function getStatusMessage(): string;

    public function getStatusCode(): int;

    public function publishStatusCode(): self;

    public function addXmlHeaders(): self;

    public function addJsonHeaders(): self;

    public function addCssHeaders(): self;

    public function addJsHeaders(): self;

    public function addHtmlHeaders(): self;

    public function setStatus(OwoServerStatusInterface $status): self;

    public function getStatus(): ?OwoServerStatusInterface;

    public function setBody($body): self;

    public function getBody();

    public function setHeaders(array $headers): self;

    public function emptyHeaders(): self;

    public function addHeaders(array $headers): self;

    public function addHeader($key, $value): self;

    public function getHeaders(): array;

    public function hasHeader($key): bool;

    public function removeHeader($key): self;

    public function getHeader($key, $default = null);

    public function setCookies(array $cookies): self;

    public function emptyCookies(): self;

    public function addCookies(array $cookies): self;

    public function addCookie($key, $value): self;

    public function getCookies(): array;

    public function hasCookie($key): bool;

    public function removeCookie($key): self;

    public function getCookie($key, $default = null);

    public function setParams(array $params): self;

    public function emptyParams(): self;

    public function addParams(array $params): self;

    public function addParam($key, $value): self;

    public function getParams(): array;

    public function hasParam($key): bool;

    public function removeParam($key): self;

    public function getParam($key, $default = null);
}
