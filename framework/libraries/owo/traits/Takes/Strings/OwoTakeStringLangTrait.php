<?php

namespace framework\libraries\owo\traits\Takes\Strings;


trait OwoTakeStringLangTrait
{
    protected $lang = null;

    public function __construct(string $lang)
    {
        $this->setLang($lang);
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }
}
