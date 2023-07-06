<?php

namespace framework\libraries\owo\classes\Clients;

use framework\libraries\owo\classes\Servers\OwoServerResponse;
use framework\libraries\owo\classes\Helpers\OwoHelperResource;
use framework\libraries\owo\classes\Clients\OwoClient;

use framework\libraries\owo\interfaces\Clients\OwoClientStreamInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerResponseInterface;
use framework\libraries\owo\interfaces\Servers\OwoServerRequestInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeParamsTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringModeTrait;


class OwoClientStream extends OwoClient implements OwoClientStreamInterface
{
    use OwoTakeArrayKeyMixeParamsTrait;
    use OwoTakeStringModeTrait;

    public const CLIENT_STREAM_DEFAULT_HEADERS = ['Content-Type' => 'multipart/form-data'];

    public const CLIENT_STREAM_DEFAULT_MODE = 'rb';

    public function __construct(array $headers = [], array $options = [], array $params = [])
    {
        parent::__construct(static::CLIENT_STREAM_DEFAULT_HEADERS, $options);
        $this->addHeaders($headers)->setParams($params);
        $this->setMode(static::CLIENT_STREAM_DEFAULT_MODE);
    }

    public function send(OwoServerRequestInterface $request, array $options = []): ?OwoServerResponseInterface
    {
        $options['http'] = $options['http'] ?? [];
        $options['http']['content'] = \http_build_query($request->getInputs());
        $options['http']['method'] = $request->getMethod();

        $headers = \array_merge($this->headers, $request->getHeaders());
        $options['http']['header'] = \implode('\r\n', $this->normalizeHeaders($headers));

        $outcome = $this->executeStream($request->getUrl(), $options);
        if (true !== \is_null($outcome)) {
            $response = OwoServerResponse::ensureResponse($outcome);
            return $response;
        }
        return null;
    }

    public function executeStream(string $url, array $options = [], array $params = []): ?string
    {
        $context = $this->getStreamContext($options, $params);
        if (true !== \is_null($stream = OwoHelperResource::createStream($url, $this->mode, $context))) {
            $contents = OwoHelperResource::getStreamContents($stream);
            \fclose($stream);
            return $contents;
        }
        return null;
    }

    public function getStreamContext(array $options = [], array $params = []): \resource
    {
        list($options, $params) = [
            \array_merge_recursive($this->params, $params),
            \array_merge_recursive($this->options, $options),
        ];
        return \stream_context_create($options, $params);
    }
}
