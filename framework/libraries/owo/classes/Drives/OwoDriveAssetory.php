<?php

namespace framework\libraries\owo\classes\Drives;

use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;

use framework\libraries\owo\interfaces\Drives\OwoDriveAssetoryInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringDirTrait;


class OwoDriveAssetory implements OwoDriveAssetoryInterface
{
    use OwoTakeStringDirTrait;

    public function __construct(string $dir = '.')
    {
        $this->setDir($dir);
    }

    public function render(string $asset, array $data = [], bool $extract = true): ?string
    {
        return OwoHelperBackrest::loadContentsPHP($this->assetPath($asset), $data, $extract);
    }

    public function assetPath(string $asset): string
    {
        return $this->dir.'/'.$asset.'.php';
    }
}
