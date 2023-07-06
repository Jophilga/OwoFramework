<?php

namespace framework\libraries\owo\interfaces\Servers;


interface OwoServerRewriterInterface
{
    public function rewriteUrlByQueryParam(string $url, string $param): self;

    public function rewriteUrlByPath(string $url): self;

    public function getPathMeasure(string $path, array &$matches = null): ?callable;

    public function setMeasures(array $measures): self;

    public function emptyMeasures(): self;

    public function addMeasures(array $measures): self;

    public function addMeasure($key, callable $value): self;

    public function getMeasures(): array;

    public function hasMeasure($key): bool;

    public function removeMeasure($key): self;

    public function getMeasure($key, $default = null): ?callable;
}
