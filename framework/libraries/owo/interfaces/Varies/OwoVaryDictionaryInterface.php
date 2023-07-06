<?php

namespace framework\libraries\owo\interfaces\Varies;


interface OwoVaryDictionaryInterface
{
    public function setLangFromGlobals(): self;

    public function loadTermsFromLangJSON(string $dir): self;

    public function loadTermsFromLangDOT(string $dir): self;

    public function loadTermsFromJSON(string $filejson): self;

    public function loadTermsFromDOT(string $filedot): self;

    public function setLang(string $lang): self;

    public function getLang(): ?string;

    public function setTerms(array $terms): self;

    public function emptyTerms(): self;

    public function addTerms(array $terms): self;

    public function addTerm($key, string $value): self;

    public function getTerms(): array;

    public function hasTerm($key): bool;

    public function removeTerm($key): self;

    public function getTerm($key, $default = null): ?string;
}
