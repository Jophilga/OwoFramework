<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeHeadersTrait
{
    protected $headers = [];

    public function __construct(array $headers = [])
    {
        $this->setHeaders($headers);
    }

    public function setHeaders(array $headers): self
    {
        return $this->emptyHeaders()->addHeaders($headers);
    }

    public function emptyHeaders(): self
    {
        $this->headers = [];
        return $this;
    }

    public function addHeaders(array $headers): self
    {
        foreach ($headers as $key => $value) $this->addHeader($key, $value);
        return $this;
    }

    public function addHeader($key, $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->headers, $key));
    }

    public function removeHeader($key): self
    {
        unset($this->headers[$key]);
        return $this;
    }

    public function getHeader($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
}
