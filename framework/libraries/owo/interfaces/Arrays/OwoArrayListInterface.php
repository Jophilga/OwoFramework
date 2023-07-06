<?php

namespace framework\libraries\owo\interfaces\Arrays;


interface OwoArrayListInterface
{
    public function countItems(): int;

    public function sortItems(callable $callback): bool;

    public function isEmpty(): bool;

    public function setItems(array $items): self;

    public function emptyItems(): self;

    public function addItems(array $items): self;

    public function addItem($item): self;

    public function removeItem($item): self;

    public function hasItem($item): bool;

    public function getItems(): array;
}
