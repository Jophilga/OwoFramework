<?php

namespace framework\libraries\owo\classes\Servers;

use framework\libraries\owo\interfaces\Servers\OwoServerStatusInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringMessageTrait;
use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerCodeTrait;


class OwoServerStatus implements OwoServerStatusInterface
{
    use OwoTakeStringMessageTrait;
    use OwoTakeIntegerCodeTrait;

    public const SERVER_STATUS_DEFAULT_MESSAGE = 'Unknown Status';

    public const SERVER_STATUS_HTTP_MESSAGES = [
        100 => 'Continue', 101 => 'Switching Protocols',

        200 => 'OK', 201 => 'Created', 202 => 'Accepted',
        203 => 'Non-Authoritative Information', 204 => 'No Content',
        205 => 'Reset Content', 206 => 'Partial Content',

        300 => 'Multiple Choices', 301 => 'Moved Permanently',
        302 => 'Found', 303 => 'See Other',
        304 => 'Not Modified', 305 => 'Use Proxy',
        306 => '(Unused)', 307 => 'Temporary Redirect',

        400 => 'Bad Request', 401 => 'Unauthorized',
        402 => 'Payment Required', 403 => 'Forbidden',
        404 => 'Not Found', 405 => 'Method Not Allowed',
        406 => 'Not Acceptable', 407 => 'Proxy Authentication Required',
        408 => 'Request Timeout', 409 => 'Conflict',
        410 => 'Gone', 411 => 'Length Required',
        412 => 'Precondition Failed', 413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed',

        500 => 'Internal Server Error', 501 => 'Not Implemented',
        502 => 'Bad Gateway', 503 => 'Service Unavailable',
        504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported',
    ];

    public function __construct(int $code)
    {
        $this->setCode($code)->setMessageFromCode();
    }

    public function setMessageFromCode(): self
    {
        $message = $this->getCodeMessage($this->code);
        return $this->setMessage($message);
    }

    public static function getCodeMessage(int $code, $default = null): string
    {
        return static::SERVER_STATUS_HTTP_MESSAGES[$code] ?? $default ??
            static::SERVER_STATUS_DEFAULT_MESSAGE;
    }
}
