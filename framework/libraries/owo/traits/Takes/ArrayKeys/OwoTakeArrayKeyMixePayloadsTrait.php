<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;


trait OwoTakeArrayKeyMixePayloadsTrait
{
    protected $payloads = [];

    public function __construct(array $payloads = [])
    {
        $this->setPayloads($payloads);
    }

    public function setPayloads(array $payloads): self
    {
        return $this->emptyPayloads()->addPayloads($payloads);
    }

    public function emptyPayloads(): self
    {
        $this->payloads = [];
        return $this;
    }

    public function addPayloads(array $payloads): self
    {
        foreach ($payloads as $key => $value) $this->addPayload($key, $value);
        return $this;
    }

    public function addPayload($key, $value): self
    {
        $this->payloads[$key] = $value;
        return $this;
    }

    public function getPayloads(): array
    {
        return $this->payloads;
    }

    public function hasPayload($key): bool
    {
        return (true === isset($this->payloads[$key]));
    }

    public function removePayload($key): self
    {
        unset($this->payloads[$key]);
        return $this;
    }

    public function getPayload($key, $default = null)
    {
        return $this->payloads[$key] ?? $default;
    }
}
