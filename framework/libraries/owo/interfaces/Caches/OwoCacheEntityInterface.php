<?php

namespace framework\libraries\owo\interfaces\Caches;


interface OwoCacheEntityInterface
{
    public function updateContentNow(string $content): self;

    public function usable(int $duration): bool;

    public function lifemtime(): int;

    public function getUat(): ?int;

    public function getContent(): ?string;

    public function setName(string $name): self;

    public function getName(): ?string;
}
