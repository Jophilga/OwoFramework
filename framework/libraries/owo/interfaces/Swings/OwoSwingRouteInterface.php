<?php

namespace framework\libraries\owo\interfaces\Swings;

use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;


interface OwoSwingRouteInterface
{
    public function action($action): self;

    public function alikes(OwoSwingRouteInterface $route): bool;

    public function carry(OwoServerRequestInterface $request): ?OwoServerResponseInterface;

    public function covers(OwoServerRequestInterface $request, array &$params = null): bool;

    public function links(string $method, string $path, array &$params = null): bool;

    public function takes(string $path, array &$params = null): bool;

    public function accepts(string $method): bool;

    public function listParams(): array;

    public function getHandler(): callable;

    public function pattern(): string;

    public function url(array $params = []): string;

    public function setName(string $name): self;

    public function getName(): ?string;

    public function setPath(string $path): self;

    public function getPath(): ?string;

    public function setAction(callable $action): self;

    public function getAction(): ?callable;

    public function setMethod(string $method): self;

    public function getMethod(): ?string;
}
