<?php

namespace framework\libraries\owo\classes\Clients;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Clients\OwoClient;

use framework\libraries\owo\interfaces\Clients\OwoClientCurlInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonCurlHandlerTrait;


class OwoClientCurl extends OwoClient implements OwoClientCurlInterface
{
    use OwoMakeCommonCurlHandlerTrait;

    protected $response_headers = [];

    public const CLIENT_CURL_POST_METHODS = ['POST', 'PUT', 'PATCH'];

    public const CLIENT_CURL_DEFAULT_HEADERS = ['Content-Type' => 'multipart/form-data'];

    public const CLIENT_CURL_DEFAULT_OPTIONS = [
        \CURLOPT_FOLLOWLOCATION => true,
        \CURLOPT_SSL_VERIFYHOST => 2,
        \CURLOPT_SSL_VERIFYPEER => true,
        \CURLOPT_PROTOCOLS => \CURLPROTO_HTTPS | \CURLPROTO_HTTP,
        \CURLOPT_ENCODING => '',
        \CURLOPT_TIMEOUT => 30,
        \CURLOPT_HEADER => false,
        \CURLOPT_AUTOREFERER => true,
        \CURLOPT_SAFE_UPLOAD => true,
        \CURLOPT_CONNECTTIMEOUT => 5,
        \CURLOPT_RETURNTRANSFER => true,
    ];

    public function __construct(array $headers = [], array $options = [])
    {
        parent::__construct(static::CLIENT_CURL_DEFAULT_HEADERS, static::CLIENT_CURL_DEFAULT_OPTIONS);
        $this->addHeaders($headers)->addOptions($options);
    }

    public function send(OwoServerRequestInterface $request, array $options = []): ?OwoServerResponseInterface
    {
        $options[\CURLOPT_RETURNTRANSFER] = true;
        $options[\CURLOPT_CUSTOMREQUEST] = $method = $request->getMethod();
        $options[\CURLOPT_HEADERFUNCTION] = [$this, 'setResponseHeaders'];
        $options[\CURLOPT_URL] = $request->getUrl();

        $headers = \array_merge($this->headers, $request->getHeaders());
        $options[\CURLOPT_HTTPHEADER] = $this->normalizeHeaders($headers);

        if (true === \in_array($method, static::CLIENT_CURL_POST_METHODS, true)) {
            $options[\CURLOPT_POSTFIELDS] = $request->getInputs();
        }
        $prepared_options = $this->getPreparedOptions($options);

        $outcome = $this->resetReponseHeaders()->executeCurl($prepared_options, $error);
        if (true !== \is_null($outcome)) {
            $response = OwoServerResponse::ensureResponse($outcome);
            $code = $this->getCurlInfo(\CURLINFO_RESPONSE_CODE, 417);
            $response->setStatusWithCode($code)->addHeaders($this->response_headers);
            return $response;
        }
        return null;
    }

    public function resetReponseHeaders(): self
    {
        $this->response_headers = [];
        return $this;
    }

    protected function getPreparedOptions(array $options = []): array
    {
        $prepared_options = $this->options;
        foreach ($options as $key => $value) $prepared_options[$key] = $value;
        return $prepared_options;
    }

    protected function setResponseHeaders($curl, $header): int
    {
        if (false !== \stripos($header, ':')) {
            list($key, $value) = \explode(':', $header, 2);
            $this->response_headers[\trim($key)] = \trim($value);
        }
        else $this->response_headers[] = \trim($header);
        return OwoHelperString::length($header);
    }
}
