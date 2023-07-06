<?php

namespace framework\libraries\owo\interfaces\Binders;

use framework\libraries\owo\interfaces\Commons\OwoCommonParametrizerInterface;


interface OwoBinderChannelInterface extends OwoCommonParametrizerInterface
{
    public function initializeConn(): self;

    public function hasAuthenticatedConn(): bool;

    public function hasInitializedConn(): bool;

    public function ensureCreateConn();

    public function ensureHasParams(array $names): self;

    public function authenticateConn(): self;

    public function connect(): self;

    public function disconnect(): self;

    public function createConn();

    public function getAuth(): ?bool;

    public function setOptions(array $options): self;

    public function emptyOptions(): self;

    public function addOptions(array $options): self;

    public function addOption($key, $value): self;

    public function getOptions(): array;

    public function hasOption($key): bool;

    public function removeOption($key): self;

    public function getOption($key, $default = null);

    public function setConn($conn): self;

    public function getConn();
}
