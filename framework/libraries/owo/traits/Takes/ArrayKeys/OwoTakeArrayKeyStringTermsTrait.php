<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyStringTermsTrait
{
    protected $terms = [];

    public function __construct(array $terms = [])
    {
        $this->setTerms($terms);
    }

    public function setTerms(array $terms): self
    {
        return $this->emptyTerms()->addTerms($terms);
    }

    public function emptyTerms(): self
    {
        $this->terms = [];
        return $this;
    }

    public function addTerms(array $terms): self
    {
        foreach ($terms as $key => $value) $this->addTerm($key, $value);
        return $this;
    }

    public function addTerm($key, string $value): self
    {
        $this->terms[$key] = $value;
        return $this;
    }

    public function getTerms(): array
    {
        return $this->terms;
    }

    public function hasTerm($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->terms, $key));
    }

    public function removeTerm($key): self
    {
        unset($this->terms[$key]);
        return $this;
    }

    public function getTerm($key, $default = null): ?string
    {
        return $this->terms[$key] ?? $default;
    }
}
