<?php

namespace framework\libraries\owo\classes\Binders;

use framework\libraries\owo\classes\Binders\OwoBinderChannel;
use framework\libraries\owo\classes\Helpers\OwoHelperSystem;

use framework\libraries\owo\interfaces\Binders\OwoBinderSSHInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeMethodsTrait;
use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeCallbacksTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringErrorTrait;


class OwoBinderSSH extends OwoBinderChannel implements OwoBinderSSHInterface
{
    use OwoTakeArrayKeyMixeCallbacksTrait;
    use OwoTakeArrayKeyMixeMethodsTrait;
    use OwoTakeStringErrorTrait;

    public const BINDER_SSH_AUTH_TYPE_AGENT  = 'agent';

    public const BINDER_SSH_AUTH_TYPE_NONE  = 'none';

    public const BINDER_SSH_AUTH_TYPE_PASSWORD  = 'password';

    public const BINDER_SSH_EXTENSION  = 'libssh2';

    public function __construct(array $params = [], array $options = [])
    {
        parent::__construct($params, $options);
        $auth_type = static::BINDER_SSH_AUTH_TYPE_PASSWORD;
        $this->setParamAuthType($auth_type);
    }

    public function authenticateConn(): self
    {
        $auth_type = $this->ensureHasParams(['auth_type'])->getParamAuthType();
        switch ($auth_type = OwoHelperString::lowerCase($auth_type)) {
            case static::BINDER_SSH_AUTH_TYPE_AGENT: $this->authenticateWithAgent(); break;
            case static::BINDER_SSH_AUTH_TYPE_NONE: $this->authenticateAsNone(); break;
            case static::BINDER_SSH_AUTH_TYPE_PASSWORD: $this->authenticateWithPassword(); break;
            default:
                $message = \sprintf('Unsupported Auth Type [%s] Found', $auth_type);
                static::throwInvalidArgumentException($message);
            break;
        }
        return $this;
    }

    public function connect(): self
    {
        return $this->requireLoadedExtensionSSH()->ensureAuthenticatedConn();
    }

    public function disconnect(): self
    {
        if (true === $this->requireLoadedExtensionSSH()->hasInitializedConn()) {
            if (true === \ssh2_disconnect($this->conn)) {
                list($this->conn, $this->auth) = [ null, false, ];
            }
        }
        return $this;
    }

    public function createConn()
    {
        $this->requireLoadedExtensionSSH()->ensureHasParams(['host', 'port']);
        $conn = \ssh2_connect($this->getParamHost(), $this->getParamPort(), $this->methods, $this->callbacks);
        return (false !== $conn) ? $conn : null;
    }

    public function setParamAuthType(string $auth_type): self
    {
        return $this->addParam('auth_type', $auth_type);
    }

    public function getParamAuthType($default = null): ?string
    {
        return $this->getParam('auth_type', $default);
    }

    public function execute(string $command, array $args = []): ?string
    {
        $this->connect();
        if (false === ($stream = \ssh2_exec($this->conn, $command, ...$args))) {
            return null;
        }

        $stream_sio = \ssh2_fetch_stream($stream, \SSH2_STREAM_STDIO);
        if (false === \stream_set_blocking($stream_sio, true)) {
            return null;
        }

        $stream_error = \ssh2_fetch_stream($stream, \SSH2_STREAM_STDERR);
        if (false === \stream_set_blocking($stream_error, true)) {
            return null;
        }

        if (false === ($contents_sio = \stream_get_contents($stream_sio))) {
            $contents_sio = null;
        }

        if (false !== ($contents_error = \stream_get_contents($stream_error))) {
            $this->setError($contents_error);
        }

        \array_map('\\fclose', [$stream, $stream_sio, $stream_error]);
        return $contents_sio;
    }

    public function shell(string $command, string $type = 'xterm', array $args = []): bool
    {
        $this->connect();
        if (false !== ($stream = \ssh2_shell($this->conn, $type, ...$args))) {
            $rbytes = \fwrite($stream, \sprintf('%s%s', $command, \PHP_EOL));
            \fclose($stream);
            return (false !== $rbytes);
        }
        return false;
    }

    public function authenticateWithAgent(): self
    {
        if (true !== $this->requireLoadedExtensionSSH()->hasAuthenticatedConn()) {
            $this->ensureInitializedConn()->ensureHasParams(['user']);
            if (true === \ssh2_auth_agent($this->conn, $this->getParamUser())) {
                $this->setAuth(true);
            }
        }
        return $this;
    }

    public function authenticateAsNone(): self
    {
        if (true !== $this->requireLoadedExtensionSSH()->hasAuthenticatedConn()) {
            $this->ensureInitializedConn()->ensureHasParams(['user']);
            if (true === \ssh2_auth_none($this->conn, $this->getParamUser())) {
                $this->setAuth(true);
            }
        }
        return $this;
    }

    public function authenticateWithPassword(): self
    {
        if (true !== $this->requireLoadedExtensionSSH()->hasAuthenticatedConn()) {
            $this->ensureInitializedConn()->ensureHasParams(['user', 'pass']);
            if (true === \ssh2_auth_password($this->conn, $this->getParamUser(), $this->getParamHost())) {
                $this->setAuth(true);
            }
        }
        return $this;
    }

    public function usesAuthTypeAgent(): bool
    {
        return (true === $this->usesAuthType(static::BINDER_SSH_AUTH_TYPE_AGENT));
    }

    public function usesAuthTypeNone(): bool
    {
        return (true === $this->usesAuthType(static::BINDER_SSH_AUTH_TYPE_NONE));
    }

    public function usesAuthTypePassword(): bool
    {
        return (true === $this->usesAuthType(static::BINDER_SSH_AUTH_TYPE_PASSWORD));
    }

    public function usesAuthType(string $auth_type): bool
    {
        if (true !== \is_null($param_auth_type = $this->getParamDriver())) {
            return (OwoHelperString::lowerCase($auth_type) === OwoHelperString::lowerCase($param_auth_type));
        }
        return false;
    }

    protected function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    protected function requireLoadedExtensionSSH(): self
    {
        $extension = static::BINDER_SSH_EXTENSION;
        if (true !==  OwoHelperSystem::isLoadedExtension($extension)) {
            $message = \sprintf('Loaded Extension [%s] Not Found', $extension);
            static::throwRuntimeException($message);
        }
        return $this;
    }
}
