<?php

namespace framework\libraries\owo\classes\Servers;

use framework\libraries\owo\classes\Swings\OwoSwingCourse;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelperUrl;

use framework\libraries\owo\interfaces\Swings\OwoSwingRouteInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;
use framework\libraries\owo\interfaces\Swings\OwoSwingCourseInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeHeadersTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeInputsTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeUploadsTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeCookiesTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringUrlTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringMethodTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonAttributorTrait;


class OwoServerRequest implements OwoServerRequestInterface
{
    use OwoTakeArrayKeyMixeHeadersTrait;
    use OwoTakeArrayKeyMixeInputsTrait;
    use OwoTakeArrayKeyMixeUploadsTrait;
    use OwoTakeArrayKeyMixeCookiesTrait;
    use OwoTakeStringUrlTrait;
    use OwoTakeStringMethodTrait;

    use OwoMakeCommonAttributorTrait;

    protected $course = null;

    public function __construct(string $url = '/', string $method = 'GET', array $headers = [])
    {
        $this->setUrl($url)->setMethod($method)->setHeaders($headers);
    }

    public function isXMLHttpRequest(): bool
    {
        return ('XMLHttpRequest' === $this->getHeader('X-Requested-With'));
    }

    public function map(OwoSwingRouteInterface $route, array $params = []): self
    {
        return $this->setCourse(new OwoSwingCourse( $route, $params));
    }

    public function setCourse(OwoSwingCourseInterface $course): self
    {
        $this->course = $course;
        return $this;
    }

    public function getCourse(): ?OwoSwingCourseInterface
    {
        return $this->course;
    }

    public function path($default = null): ?string
    {
        return OwoHelperUrl::path($this->url, $default);
    }

    public function host($default = null): ?string
    {
        return OwoHelperUrl::host($this->url, $default);
    }

    public function port($default = null): ?int
    {
        return OwoHelperUrl::port($this->url, $default);
    }

    public function param(string $name, $default = null): ?string
    {
        return OwoHelperUrl::param($this->url, $name, $default);
    }

    public function scheme($default = null): ?string
    {
        return OwoHelperUrl::scheme($this->url, $default);
    }

    public function all(): array
    {
        return OwoHelperArray::mergeProperly($this->params(), $this->getInputs());
    }

    public function params(): array
    {
        return OwoHelperUrl::params($this->url);
    }
}
