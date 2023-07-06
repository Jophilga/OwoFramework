<?php

namespace framework\libraries\owo\classes\Binders;

use framework\libraries\owo\classes\Binders\OwoBinderChannel;

use framework\libraries\owo\interfaces\Binders\OwoBinderFTPInterface;


class OwoBinderFTP extends OwoBinderChannel implements OwoBinderFTPInterface
{
    public const BINDER_FTP_DEFAULT_PARAM_SECURE = true;

    public const BINDER_FTP_DEFAULT_PARAM_PORT = 21;

    public const BINDER_FTP_DEFAULT_PARAM_TIMEOUT = 60;

    public const BINDER_FTP_SCHEME = 'ftp';

    public function __construct(array $params = [], array $options = [])
    {
        parent::__construct($params, $options);
        $secure = static::BINDER_FTP_DEFAULT_PARAM_SECURE;
        $this->setParamSecure($secure);

        $timeout = static::BINDER_FTP_DEFAULT_PARAM_TIMEOUT;
        $this->setParamTimeout($timeout);

        $port = static::BINDER_FTP_DEFAULT_PARAM_TIMEOUT;
        $this->setParamPort($port);
    }

    public function authenticateConn(): self
    {
        if (true !== $this->hasAuthenticatedConn()) {
            $this->ensureInitializedConn()->ensureHasParams(['user', 'pass']);
            if (true === \ftp_login($this->conn, $this->getParamUser(), $this->getParamPass())) {
                $this->setAuth(true);
            }
        }
        return $this;
    }

    public function connect(): self
    {
        return $this->ensureAuthenticatedConn();
    }

    public function disconnect(): self
    {
        if (true === $this->hasInitializedConn()) {
            if (false === \ftp_close($this->conn)) {
                static::throwRuntimeException('Conn Closing Failed');
            }
            list($this->conn, $this->auth) = [ null, false, ];
        }
        return $this;
    }

    public function createConn()
    {
        if (true === $this->getParamSecure(static::BINDER_FTP_DEFAULT_PARAM_SECURE)) {
            return $this->createSecuredWithSSLConn();
        }
        return $this->createUnsecuredConn();
    }

    public function createSecuredWithSSLConn(): \resource
    {
        $this->ensureHasParams(['host', 'port', 'timeout']);
        $resource = \ftp_ssl_connect($this->getParamHost(), $this->getParamPort(), $this->getParamTimeout());
        if (false === $resource) {
            static::throwRuntimeException('Secured SSL Conn Creation Failed');
        }
        return $resource;
    }

    public function createUnsecuredConn(): \resource
    {
        $this->ensureHasParams(['host', 'port', 'timeout']);
        $resource = \ftp_connect($this->getParamHost(), $this->getParamPort(), $this->getParamTimeout());
        if (false === $resource) {
            static::throwRuntimeException('Conn Creation Failed');
        }
        return $resource;
    }

    public function getUrl(): string
    {
        $url = \sprintf('%s://', static::BINDER_FTP_SCHEME);
        if (true === $this->hasParam('user')) {
            $url = \sprintf('%s%s', $url, $this->getParam('user'));

            if (true === $this->hasParam('pass')) {
                $url = \sprintf('%s:%s', $url, $this->getParam('pass'));
            }
            $url = \sprintf('%s@', $url);
        }

        if (true === $this->hasParam('host')) {
            $url = \sprintf('%s%s', $url, $this->getParam('host'));

            if (true === $this->hasParam('port')) {
                $url = \sprintf('%s:%d', $url, $this->getParam('port'));
            }
        }
        return $url;
    }

    public function setParamTimeout(int $timeout): self
    {
        return $this->addParam('timeout', $timeout);
    }

    public function getParamTimeout($default = null): ?int
    {
        return $this->getParam('timeout', $default);
    }

    public function setParamSecure(bool $secure): self
    {
        return $this->addParam('secure', $secure);
    }

    public function getParamSecure($default = null): ?bool
    {
        return $this->getParam('secure', $default);
    }
}
