<?php

namespace framework\libraries\owo\classes\Tuners;

use framework\libraries\owo\classes\Helpers\OwoHelperDecoder;
use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;
use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Tuners\OwoTunerConfiguratorInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeConfigsTrait;
use framework\libraries\owo\traits\Takes\Arrays\OwoTakeArrayStringIgnoresTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoTunerConfigurator implements OwoTunerConfiguratorInterface
{
    use OwoTakeArrayStringIgnoresTrait;
    use OwoTakeArrayKeyMixeConfigsTrait;

    use OwoMakeCommonThrowerTrait;

    public function __construct(array $configs = [], array $ignores = [])
    {
        $this->setConfigs($configs)->setIgnores($ignores);
    }

    public function loadJSON(string $filejson): self
    {
        OwoHelperBackrest::ensureReadable($filejson);
        $configs = $this->ensureDecodeJson($this->ensureLoadContents($filejson));
        return $this->addConfigs($configs);
    }

    public function loadDOT(string $filedot): self
    {
        OwoHelperBackrest::ensureReadable($filedot);
        $configs = OwoHelperBackrest::loadContentsDOT($filedot, $this->ignores);
        if (true === \is_null($configs)) {
            static::throwRuntimeException(\sprintf('Load DOT [%s] Failed', $filedot));
        }
        return $this->addConfigs($configs);
    }

    public function search(string $key, $default = null)
    {
        return \array_reduce(\explode('.', $key), function($carry, $item) use ($default) {
            return $carry[$item] ?? $default;
        }, $this->configs);
    }

    public function shouldIgnore(string $line): bool
    {
        return (true === OwoHelperString::startsWith(\trim($line), $this->ignores));
    }

    public function addIgnore(string $ignore): self
    {
        if (true !== $this->hasIgnore($ignore)) $this->ignores[] = $ignore;
        return $this;
    }

    protected function ensureLoadContents(string $file): string
    {
        $contents = OwoHelperBackrest::loadContents($file);
        if (true === \is_null($contents)) {
            $message = \sprintf('Loading [%s] Contents Failed', $file);
            static::throwRuntimeException($message);
        }
        return $contents;
    }

    protected function ensureDecodeJson(string $json): array
    {
        $data = OwoHelperDecoder::decodeJsonToArray($json);
        if (true === \is_null($data)) {
            $message = \sprintf('Decoding JSON [%s] Failed', $file);
            static::throwRuntimeException($message);
        }
        return $data;
    }
}
