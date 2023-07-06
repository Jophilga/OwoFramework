<?php

namespace framework\libraries\owo\traits\Takes\Arrays;


trait OwoTakeArrayStringIgnoresTrait
{
    protected $ignores = [];

    public function __construct(array $ignores = [])
    {
        $this->setIgnores($ignores);
    }

    public function setIgnores(array $ignores): self
    {
        return $this->emptyIgnores()->addIgnores($ignores);
    }

    public function emptyIgnores(): self
    {
        $this->ignores = [];
        return $this;
    }

    public function addIgnores(array $ignores): self
    {
        foreach ($ignores as $ignore) $this->addIgnore($ignore);
        return $this;
    }

    public function addIgnore(string $ignore): self
    {
        $this->ignores[] = $ignore;
        return $this;
    }

    public function removeIgnore(string $ignore): self
    {
        $ignores = \array_filter($this->ignores, function ($item) use ($ignore) {
            return ($ignore !== $item);
        });
        $this->ignores = $ignores;
        return $this;
    }

    public function hasIgnore(string $ignore): bool
    {
        return (true === \in_array($ignore, $this->ignores, true));
    }

    public function getIgnores(): array
    {
        return $this->ignores;
    }
}
