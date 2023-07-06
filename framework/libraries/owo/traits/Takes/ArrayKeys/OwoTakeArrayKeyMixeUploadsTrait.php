<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeUploadsTrait
{
    protected $uploads = [];

    public function __construct(array $uploads = [])
    {
        $this->setUploads($uploads);
    }

    public function setUploads(array $uploads): self
    {
        return $this->emptyUploads()->addUploads($uploads);
    }

    public function emptyUploads(): self
    {
        $this->uploads = [];
        return $this;
    }

    public function addUploads(array $uploads): self
    {
        foreach ($uploads as $key => $value) $this->addUpload($key, $value);
        return $this;
    }

    public function addUpload($key, $value): self
    {
        $this->uploads[$key] = $value;
        return $this;
    }

    public function getUploads(): array
    {
        return $this->uploads;
    }

    public function hasUpload($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->uploads, $key));
    }

    public function removeUpload($key): self
    {
        unset($this->uploads[$key]);
        return $this;
    }

    public function getUpload($key, $default = null)
    {
        return $this->uploads[$key] ?? $default;
    }
}
