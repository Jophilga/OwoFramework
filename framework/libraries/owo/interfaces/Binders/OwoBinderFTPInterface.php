<?php

namespace framework\libraries\owo\interfaces\Binders;

use framework\libraries\owo\interfaces\Binders\OwoBinderChannelInterface;


interface OwoBinderFTPInterface extends OwoBinderChannelInterface
{
    public function setParamTimeout(int $timeout): self;

    public function getParamTimeout($default = null): ?int;

    public function setParamSecure(bool $secure): self;

    public function getParamSecure($default = null): ?bool;

    public function getUrl(): string;

    public function createSecuredWithSSLConn(): \resource;

    public function createUnsecuredConn(): \resource;
}
