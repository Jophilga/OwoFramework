<?php

namespace framework\libraries\owo\classes\Varies;

use framework\libraries\owo\interfaces\Commons\OwoCommonDisplayableInterface;
use framework\libraries\owo\interfaces\Varies\OwoVaryDisplayerInterface;


class OwoVaryDisplayer implements OwoVaryDisplayerInterface
{
    protected $cleaned_buffer = null;

    public function startCompressedOutputRecord(): bool
    {
        return (true === \ob_start('\\ob_gzhandler'));
    }

    public function registerObEndFlushAsShutdown(array $args = []): self
    {
        \register_shutdown_function('\\ob_end_flush', ...$args);
        return $this;
    }

    public function getOutputRecord(): ?string
    {
        if (false !== ($contents = \ob_get_contents())) {
            return $contents;
        }
        return  null;
    }

    public function cleanThenDisplayOnOutput($data): self
    {
        $this->cleaned_buffer = $this->getThenCleanOutputRecord();
        return $this->displayOnOutput($data);
    }

    public function getThenCleanOutputRecord(): ?string
    {
        if (false !== ($contents = \ob_get_clean())) {
            return $contents;
        }
        return  null;
    }

    public function displayOnOutput($data): self
    {
        \ob_start();
        if (true === \is_subclass_of($data, OwoCommonDisplayableInterface::class)) {
            $data->display($this);
        }
        else echo \strval($data);
        return $this;
    }

    public function dumpPretty(...$vars): self
    {
        \ob_start();
        echo '<pre>', \var_dump(...$vars), '</pre>';
        return $this;
    }

    public function getCleanedBuffer(): ?string
    {
        return $this->cleaned_buffer;
    }
}
