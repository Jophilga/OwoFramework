<?php

namespace framework\libraries\owo\interfaces\Tuners;


interface OwoTunerConfiguratorInterface
{
    public function loadDOT(string $filedot): self;

    public function loadJSON(string $filejson): self;

    public function search(string $key, $default = null);

    public function shouldIgnore(string $line): bool;

    public function addIgnore(string $ignore): self;

    public function setIgnores(array $ignores): self;

    public function emptyIgnores(): self;

    public function addIgnores(array $ignores): self;

    public function removeIgnore(string $ignore): self;

    public function hasIgnore(string $ignore): bool;

    public function getIgnores(): array;

    public function setConfigs(array $configs): self;

    public function emptyConfigs(): self;

    public function addConfigs(array $configs): self;

    public function addConfig($key, $value): self;

    public function getConfigs(): array;

    public function hasConfig($key): bool;

    public function removeConfig($key): self;

    public function getConfig($key, $default = null);
}
