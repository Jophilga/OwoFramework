<?php

namespace framework\libraries\owo\interfaces\Queries;


interface OwoQueryBuilderInterface
{
    public function getPreparedParams(): array;

    public function assignSections(array $sections, string $combiner = ','): self;

    public function assignSection($key, string $section, string $combiner = ','): self;

    public function getQuery(): string;

    public function setSections(array $sections): self;

    public function emptySections(): self;

    public function addSections(array $sections): self;

    public function addSection($key, string $value): self;

    public function getSections(): array;

    public function hasSection($key): bool;

    public function removeSection($key): self;

    public function getSection($key, $default = null): ?string;

    public function setAbstract(bool $abstract): self;

    public function getAbstract(): ?bool;
}
