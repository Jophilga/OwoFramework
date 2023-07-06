<?php

namespace framework\libraries\owo\interfaces\Seekers;


interface OwoSeekerPreloaderInterface
{
    public function preloadPaths(array $paths): self;

    public function preloadPath(string $path): self;

    public function shouldPreload(string $path, array &$matches = null): bool;

    public function shouldIgnore(string $path): bool;

    public function assumes(string $path, array &$matches = null): bool;

    public function getPreloadedPaths(): array;

    public function getPreloadedPath(string $name, $default = null): ?string;

    public function setThrow(bool $throw): self;

    public function getThrow(): ?bool;

    public function addIgnore(string $ignore): self;

    public function setIgnores(array $ignores): self;

    public function emptyIgnores(): self;

    public function addIgnores(array $ignores): self;

    public function removeIgnore(string $ignore): self;

    public function hasIgnore(string $ignore): bool;

    public function getIgnores(): array;

    public function setPattern(string $pattern): self;

    public function getPattern(): ?string;
}
