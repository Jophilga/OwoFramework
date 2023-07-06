<?php

namespace framework\libraries\owo\interfaces\Sessions;

use framework\libraries\owo\interfaces\Sessions\OwoSessionPrefixInterface;


interface OwoSesisionCsrfInterface
{
    public function getNewCrsfToken(int $length = 30): string;

    public function useCrsfToken(string $crsf_token): bool;

    public function hasCrsfToken(string $crsf_token): bool;

    public function deleteCrsfToken(string $crsf_token): self;

    public function deleteCrsfTokens(): self;

    public function getCrsfTokens(): array;

    public function setSession(OwoSessionPrefixInterface $session): self;

    public function getSession(): ?OwoSessionPrefixInterface;
}
