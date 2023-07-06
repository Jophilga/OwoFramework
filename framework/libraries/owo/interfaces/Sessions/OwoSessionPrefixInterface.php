<?php

namespace framework\libraries\owo\interfaces\Sessions;

use framework\libraries\owo\interfaces\Sessions\OwoSessionImitorInterface;


interface OwoSessionPrefixInterface extends OwoSessionImitorInterface
{
    public function setPrefix(string $prefix): self;

    public function getPrefix(): ?string;
}
