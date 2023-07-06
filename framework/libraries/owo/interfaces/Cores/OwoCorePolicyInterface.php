<?php

namespace framework\libraries\owo\interfaces\Cores;


interface OwoCorePolicyInterface
{
    public function getAvailableActions(): array;

    public function grants(string $action, $actor, $subject, array $args = []): bool;

    public function proceeds(string $action): bool;
}
