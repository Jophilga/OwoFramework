<?php

namespace framework\libraries\owo\interfaces\Seekers;


interface OwoSeekerAutoloaderInterface
{
    public function autoloadClass(string $class): self;

    public function getClassPath(string $class, string $dir): string;

    public function addIncludePath(string $path): self;

    public function register(bool $prepend = true): bool;

    public function unregister(): bool;

    public function getLoadedPaths(): array;

    public function getLoadedClassPath(string $class, $default = null): ?string;

    public function setThrow(bool $throw): self;

    public function getThrow(): ?bool;

    public function setDirs(array $dirs): self;

    public function emptyDirs(): self;

    public function addDirs(array $dirs): self;

    public function addDir(string $dir): self;

    public function removeDir(string $dir): self;

    public function hasDir(string $dir): bool;

    public function getDirs(): array;
}
