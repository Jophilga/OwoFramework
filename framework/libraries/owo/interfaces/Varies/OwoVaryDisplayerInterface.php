<?php

namespace framework\libraries\owo\interfaces\Varies;


interface OwoVaryDisplayerInterface
{
    public function startCompressedOutputRecord(): bool;

    public function registerObEndFlushAsShutdown(array $args = []): self;

    public function getOutputRecord(): ?string;

    public function cleanThenDisplayOnOutput($data): self;

    public function getThenCleanOutputRecord(): ?string;

    public function displayOnOutput($data): self;

    public function dumpPretty(...$vars): self;

    public function getCleanedBuffer(): ?string;
}
