<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringContentTrait
{
    protected $content = null;

    public function __construct(string $content)
    {
        $this->setContent($content);
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
