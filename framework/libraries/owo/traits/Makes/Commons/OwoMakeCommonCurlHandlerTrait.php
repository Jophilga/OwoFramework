<?php

namespace framework\libraries\owo\traits\Makes\Commons;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringUrlTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


trait OwoMakeCommonCurlHandlerTrait
{
    use OwoTakeStringUrlTrait;

    use OwoMakeCommonThrowerTrait;

    protected $curl = null;

    public function __construct(\CurlHandle $curl, string $url = null)
    {
        if (true !== \is_null($url)) $this->setUrl($url);
        $this->setCurl($curl);
    }

    public function setCurl(\CurlHandle $curl): self
    {
        $this->curl = $curl;
        return $this;
    }

    public function getCurl(): ?\CurlHandle
    {
        return $this->curl;
    }

    public function initializeCurl(): self
    {
        if (true !== $this->hasInitializedCurl()) {
            if (true === \is_null($curl = $this->createCurl())) {
                static::throwRuntimeException('Curl Creation Failed');
            }
            $this->setCurl($curl);
        }
        return $this;
    }

    public function executeCurl(array $options = [], string &$error = null): ?string
    {
        $this->resetCurlProperly();
        if (false === \curl_setopt_array($this->curl, $options)) {
            static::throwRuntimeException('Curl Options Setting Failed');
        }

        if (false !== ($outcome = \curl_exec($this->curl))) return $outcome;
        $error = $this->getCurlError();
        return null;
    }

    public function resetCurl(): self
    {
        if (true === $this->hasInitializedCurl()) \curl_reset($this->curl);
        return $this;
    }

    public function resetCurlProperly(): self
    {
        return $this->closeCurl()->ensureInitializedCurl();
    }

    public function closeCurl(): self
    {
        if (true === $this->hasInitializedCurl()) \curl_close($this->curl);
        return $this;
    }

    public function hasInitializedCurl(): bool
    {
        if (true === \is_null($this->curl)) {
            return (true === \is_subclass_of($this->curl, \CurlHandle::class));
        }
        return false;
    }

    public function getCurlInfo(int $option, $default = null)
    {
        if (true === $this->hasInitializedCurl()) {
            if (false === ($info = \curl_getinfo($this->curl, $option))) {
                $message = \sprintf('Curl Getting Info [%d] Failed', $option);
                static::throwRuntimeException($message);
            }
            return $info;
        }
        return $default;
    }

    public function getCurlInfos(): array
    {
        if (true === $this->hasInitializedCurl()) {
            if (false === ($infos = \curl_getinfo($this->curl))) {
                static::throwRuntimeException('Curl Getting Infos Failed');
            }
            return $infos;
        }
        return [];
    }

    public function getCurlError(): ?string
    {
        if (true === $this->hasInitializedCurl()) return \curl_error($this->curl);
        return null;
    }

    public function createCurl(): ?\CurlHandle
    {
        if (false !== ($curl = \curl_init($this->url))) return $curl;
        return null;
    }

    protected function ensureInitializedCurl(): self
    {
        if (true !== $this->initializeCurl()->hasInitializedCurl()) {
            static::throwRuntimeException('Curl Initialization Failed');
        }
        return $this;
    }
}
