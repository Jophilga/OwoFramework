<?php

namespace framework\libraries\owo\classes\Binders;

use framework\libraries\owo\interfaces\Binders\OwoBinderChannelInterface;

use framework\libraries\owo\traits\Takes\Booleans\OwoTakeBooleanAuthTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeOptionsTrait;
use framework\libraries\owo\traits\Takes\Mixes\OwoTakeMixeConnTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonParametrizerTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


abstract class OwoBinderChannel implements OwoBinderChannelInterface
{
    use OwoTakeArrayKeyMixeOptionsTrait;
    use OwoTakeMixeConnTrait;
    use OwoTakeBooleanAuthTrait;

    use OwoMakeCommonParametrizerTrait;
    use OwoMakeCommonThrowerTrait;

    public const BINDER_CHANNEL_DEFAULT_AUTH = false;

    public function __construct(array $params = [], array $options = [])
    {
        $this->setAuth(static::BINDER_CHANNEL_DEFAULT_AUTH);
        $this->setParams($params)->setOptions($options);
    }

    public function initializeConn(): self
    {
        if (true !== $this->hasInitializedConn()) {
            $this->setConn($this->ensureCreateConn());
        }
        return $this;
    }

    public function hasAuthenticatedConn(): bool
    {
        return ((true === $this->hasInitializedConn()) && (true === $this->auth));
    }

    public function hasInitializedConn(): bool
    {
        return (true !== \is_null($this->conn));
    }

    public function ensureCreateConn()
    {
        if (true === empty($conn = $this->createConn())) {
            static::throwRuntimeException('Conn Creation Failed');
        }
        return $conn;
    }

    public function ensureHasParams(array $names): self
    {
        foreach ($names as $name) {
            if (true !== $this->hasParam($name)) {
                static::throwRuntimeException(\sprintf('Param [%s] Not Found', $name));
            }
        }
        return $this;
    }

    protected function ensureAuthenticatedConn(): self
    {
        $this->authenticateConn();
        if (true !== $this->hasAuthenticatedConn()) {
            static::throwRuntimeException('Unauthenticated Conn Found');
        }
        return $this;
    }

    protected function ensureInitializedConn(): self
    {
        $this->initializeConn();
        if (true !== $this->hasInitializedConn()) {
            static::throwRuntimeException('Uninitialized Conn Found');
        }
        return $this;
    }

    protected function setAuth(bool $auth): self
    {
        $this->auth = $auth;
        return $this;
    }

    abstract public function authenticateConn(): self;

    abstract public function connect(): self;

    abstract public function disconnect(): self;

    abstract public function createConn();
}
