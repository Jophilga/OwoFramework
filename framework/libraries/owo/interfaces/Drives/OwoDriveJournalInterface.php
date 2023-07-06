<?php

namespace framework\libraries\owo\interfaces\Drives;

use framework\libraries\owo\interfaces\Commons\OwoCommonDisplayableInterface;


interface OwoDriveJournalInterface extends OwoCommonDisplayableInterface
{
    public function error(string $message): bool;

    public function except(\Throwable $throwable): bool;

    public function log(string $message): bool;

    public function contents(): ?string;

    public function setPath(string $path): self;

    public function getPath(): ?string;
}
