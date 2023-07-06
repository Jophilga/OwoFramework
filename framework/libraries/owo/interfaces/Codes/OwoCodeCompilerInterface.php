<?php

namespace framework\libraries\owo\interfaces\Codes;


interface OwoCodeCompilerInterface
{
    public function transpose(string $file): ?string;

    public function translate(string $code, string $pattern, $translation): string;

    public function setClean(bool $clean): self;

    public function getClean(): ?bool;

    public function compile(string $code): string;
}
