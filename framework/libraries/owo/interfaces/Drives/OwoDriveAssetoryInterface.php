<?php

namespace framework\libraries\owo\interfaces\Drives;


interface OwoDriveAssetoryInterface
{
    public function render(string $asset, array $data = [], bool $extract = true): ?string;

    public function assetPath(string $asset): string;

    public function setDir(string $dir): self;

    public function getDir(): ?string;
}
