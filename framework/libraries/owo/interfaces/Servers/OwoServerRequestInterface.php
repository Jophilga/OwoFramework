<?php

namespace framework\libraries\owo\interfaces\Servers;

use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;
use framework\libraries\owo\interfaces\Commons\OwoCommonAttributorInterface;
use framework\libraries\owo\interfaces\Swings\OwoSwingCourseInterface;


interface OwoServerRequestInterface extends OwoCommonAttributorInterface
{
    public function isXMLHttpRequest(): bool;

    public function map(OwoSwingRouteInterface $route, array $params = []): self;

    public function setCourse(OwoSwingCourseInterface $course): self;

    public function getCourse(): ?OwoSwingCourseInterface;

    public function path($default = null): ?string;

    public function host($default = null): ?string;

    public function port($default = null): ?int;

    public function param(string $name, $default = null): ?string;

    public function scheme($default = null): ?string;

    public function all(): array;

    public function params(): array;

    public function setMethod(string $method): self;

    public function getMethod(): ?string;

    public function setUrl(string $url): self;

    public function getUrl(): ?string;

    public function setHeaders(array $headers): self;

    public function emptyHeaders(): self;

    public function addHeaders(array $headers): self;

    public function addHeader($key, $value): self;

    public function getHeaders(): array;

    public function hasHeader($key): bool;

    public function removeHeader($key): self;

    public function getHeader($key, $default = null);

    public function setInputs(array $inputs): self;

    public function emptyInputs(): self;

    public function addInputs(array $inputs): self;

    public function addInput($key, $value): self;

    public function getInputs(): array;

    public function hasInput($key): bool;

    public function removeInput($key): self;

    public function getInput($key, $default = null);

    public function setUploads(array $uploads): self;

    public function emptyUploads(): self;

    public function addUploads(array $uploads): self;

    public function addUpload($key, $value): self;

    public function getUploads(): array;

    public function hasUpload($key): bool;

    public function removeUpload($key): self;

    public function getUpload($key, $default = null);

    public function setCookies(array $cookies): self;

    public function emptyCookies(): self;

    public function addCookies(array $cookies): self;

    public function addCookie($key, $value): self;

    public function getCookies(): array;

    public function hasCookie($key): bool;

    public function removeCookie($key): self;

    public function getCookie($key, $default = null);
}
