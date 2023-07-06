<?php

namespace framework\libraries\owo\interfaces\Drives;


interface OwoDriveUploadfileInterface
{
    public function setFromPath(string $path): self;

    public function setNameFromPath(): self;

    public function size(): ?int;

    public function mimect(): ?string;

    public function move(string $destination): bool;

    public function wasUploadedProperly(): bool;

    public function setName(string $name): self;

    public function getName(): ?string;

    public function setCode(int $code): self;

    public function getCode(): ?int;

    public function setPath(string $path): self;

    public function getPath(): ?string;
}
