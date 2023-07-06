<?php

namespace framework\libraries\owo\classes\Drives;

use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;

use framework\libraries\owo\interfaces\Varies\OwoVaryDisplayerInterface;
use framework\libraries\owo\interfaces\Drives\OwoDriveJournalInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPathTrait;


class OwoDriveJournal implements OwoDriveJournalInterface
{
    use OwoTakeStringPathTrait;

    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    public function error(string $message): bool
    {
        return (true === $this->log(\sprintf('[ERROR] %s', $message)));
    }

    public function except(\Throwable $throwable): bool
    {
        $message = \sprintf('[THROWABLE] %s', \strval($throwable));
        return (true === $this->log($message));
    }

    public function log(string $message): bool
    {
        $message = \sprintf('[%s] %s <br />', \date(\DATE_COOKIE), $message);
        return (true === \error_log($message.\PHP_EOL, 3, $this->path));
    }

    public function contents(): ?string
    {
        return OwoHelperBackrest::loadContents($this->path);
    }

    public function display(OwoVaryDisplayerInterface $displayer): self
    {
        $displayer->displayOnOutput($this->contents());
        return $this;
    }
}
