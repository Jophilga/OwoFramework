<?php

namespace framework\libraries\owo\interfaces\Caches;

use framework\libraries\owo\interfaces\Caches\OwoCacheManagerInterface;


interface OwoCacheDirectoryInterface extends OwoCacheManagerInterface
{
    public function approves(string $file): bool;

    public function exists(string $name, string &$path = null): bool;

    public function filepath(string $name): string;

    public function setMode(int $mode): self;

    public function getMode(): ?int;

    public function setDir(string $dir): self;

    public function getDir(): ?string;
}
