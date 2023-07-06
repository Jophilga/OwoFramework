<?php

namespace framework\libraries\owo\interfaces\Stackers;


interface OwoStackerInterface
{
    public function reverseUnstack();

    public function stack($stackable): self;

    public function unstack();

    public function getStackableIndexes($stackable): array;

    public function getStackableByIndex(int $index, $default = null);

    public function setStackables(array $stackables): self;

    public function emptyStackables(): self;

    public function addStackables(array $stackables): self;

    public function addStackable($stackable): self;

    public function removeStackable($stackable): self;

    public function hasStackable($stackable): bool;

    public function getStackables(): array;
}
