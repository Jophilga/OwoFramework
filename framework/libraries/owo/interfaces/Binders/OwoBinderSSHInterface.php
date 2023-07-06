<?php

namespace framework\libraries\owo\interfaces\Binders;

use framework\libraries\owo\interfaces\Binders\OwoBinderChannelInterface;


interface OwoBinderSSHInterface extends OwoBinderChannelInterface
{
    public function setParamAuthType(string $auth_type): self;

    public function getParamAuthType($default = null): ?string;

    public function execute(string $command, array $args = []): ?string;

    public function shell(string $command, string $type = 'xterm', array $args = []): bool;

    public function authenticateWithAgent(): self;

    public function authenticateAsNone(): self;

    public function authenticateWithPassword(): self;

    public function usesAuthTypeAgent(): bool;

    public function usesAuthTypeNone(): bool;

    public function usesAuthTypePassword(): bool;

    public function usesAuthType(string $auth_type): bool;

    public function getError(): ?string;

    public function setMethods(array $methods): self;

    public function emptyMethods(): self;

    public function addMethods(array $methods): self;

    public function addMethod($key, $value): self;

    public function getMethods(): array;

    public function hasMethod($key): bool;

    public function removeMethod($key): self;

    public function getMethod($key, $default = null);

    public function setCallbacks(array $callbacks): self;

    public function emptyCallbacks(): self;

    public function addCallbacks(array $callbacks): self;

    public function addCallback($key, $value): self;

    public function getCallbacks(): array;

    public function hasCallback($key): bool;

    public function removeCallback($key): self;

    public function getCallback($key, $default = null);
}
