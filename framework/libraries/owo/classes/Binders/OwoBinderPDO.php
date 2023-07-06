<?php

namespace framework\libraries\owo\classes\Binders;

use framework\libraries\owo\classes\Binders\OwoBinderChannel;
use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Binders\OwoBinderPDOInterface;


class OwoBinderPDO extends OwoBinderChannel implements OwoBinderPDOInterface
{
    public const BINDER_PDO_DRIVER_POSTGRESQL = 'pgsql';

    public const BINDER_PDO_DRIVER_MYSQL = 'mysql';

    public const BINDER_PDO_DRIVER_SQLITE = 'sqlite';

    public const BINDER_PDO_DEFAULT_OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    ];

    public function __construct(array $params = [], array $options = [])
    {
        parent::__construct($params);
        $this->setOptions(static::BINDER_PDO_DEFAULT_OPTIONS);
        $this->addOptions($options);
    }

    public function authenticateConn(): self
    {
        if (true !== $this->hasAuthenticatedConn()) {
            $this->ensureInitializedConn()->setAuth(true);
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
            list($this->conn, $this->auth) = [ null, false, ];
        }
        return $this;
    }

    public function createConn()
    {
        return new \PDO($this->getDSN(), $this->getParamUser(), $this->getParamPass(), $this->options);
    }

    public function setParamDriver(string $driver): self
    {
        return $this->addParam('driver', $driver);
    }

    public function getParamDriver($default = null): ?string
    {
        return $this->getParam('driver', $default);
    }

    public function getDSN(): string
    {
        $driver = $this->ensureHasParams(['driver'])->getParamDriver();
        switch ($driver = OwoHelperString::lowerCase($driver)) {
            case static::BINDER_PDO_DRIVER_MYSQL: return $this->getDSNMySql(); break;
            case static::BINDER_PDO_DRIVER_POSTGRESQL: return $this->getDSNPostgreSql(); break;
            case static::BINDER_PDO_DRIVER_SQLITE: return $this->getDSNSqlite(); break;
            default:
                $message = \sprintf('Unsupported Driver [%s] Found', $driver);
                static::throwInvalidArgumentException($message);
            break;
        }
    }

    public function getDSNMySql(): string
    {
        $dsn = \sprintf('%s:', static::BINDER_PDO_DRIVER_MYSQL);
        if (true === $this->hasParam('dbname')) {
            $dsn = \sprintf('%sdbname=%s;', $dsn, $this->getParam('dbname'));
        }

        if (true === $this->hasParam('host')) {
            $dsn = \sprintf('%shost=%s;', $dsn, $this->getParam('host'));
        }

        if (true === $this->hasParam('port')) {
            $dsn = \sprintf('%sport=%d;', $dsn, $this->getParam('port'));
        }

        if (true === $this->hasParam('unix_socket')) {
            $dsn = \sprintf('%sunix_socket=%s;', $dsn, $this->getParam('unix_socket'));
        }

        if (true === $this->hasParam('charset')) {
            $dsn = \sprintf('%scharset=%s;', $dsn, $this->getParam('charset'));
        }
        return $dsn;
    }

    public function getDSNPostgreSql(): string
    {
        $dsn = \sprintf('%s:', static::BINDER_PDO_DRIVER_POSTGRESQL);
        if (true === $this->hasParam('dbname')) {
            $dsn = \sprintf('%sdbname=%s;', $dsn, $this->getParam('dbname'));
        }

        if (true === $this->hasParam('host')) {
            $dsn = \sprintf('%shost=%s;', $dsn, $this->getParam('host'));
        }

        if (true === $this->hasParam('port')) {
            $dsn = \sprintf('%sport=%d;', $dsn, $this->getParam('port'));
        }

        if (true === $this->hasParam('user')) {
            $dsn = \sprintf('%suser=%s;', $dsn, $this->getParam('user'));
        }

        if (true === $this->hasParam('password')) {
            $dsn = \sprintf('%spassword=%s;', $dsn, $this->getParam('password'));
        }

        if (true === $this->hasParam('sslmode')) {
            $dsn = \sprintf('%ssslmode=%s;', $dsn, $this->getParam('sslmode'));
        }
        return $dsn;
    }

    public function getDSNSqlite(): string
    {
        $dsn = \sprintf('%s:', static::BINDER_PDO_DRIVER_SQLITE);
        if (true === $this->hasParam('path')) {
            $dsn = \sprintf('%spath=%s;', $dsn, $this->getParam('path'));
        }
        return $dsn;
    }

    public function usesDriverMySql(): bool
    {
        return (true === $this->usesDriver(static::BINDER_PDO_DRIVER_SQLITE));
    }

    public function usesDriverPostgreSql(): bool
    {
        return (true === $this->usesDriver(static::BINDER_PDO_DRIVER_POSTGRESQL));
    }

    public function usesDriverSqlite(): bool
    {
        return (true === $this->usesDriver(static::BINDER_PDO_DRIVER_SQLITE));
    }

    public function usesDriver(string $driver): bool
    {
        if (true !== \is_null($param_driver = $this->getParamDriver())) {
            return (OwoHelperString::lowerCase($driver) === OwoHelperString::lowerCase($param_driver));
        }
        return false;
    }

    public function getPDO(): \PDO
    {
        return $this->connect()->getConn();
    }

    public static function isAvailableDriver(string $driver): bool
    {
        return (true === \in_array($driver, \PDO::getAvailableDrivers(), true));
    }
}
