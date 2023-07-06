<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringUrlTrait
{
    protected $url = null;

    public function __construct(string $url)
    {
        $this->setUrl($url);
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
