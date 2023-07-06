<?php

namespace framework\libraries\owo\classes\Drives;

use framework\libraries\owo\classes\Helpers\OwoHelperPath;

use framework\libraries\owo\interfaces\Drives\OwoDriveUploadfileInterface;

use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringNameTrait;
use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerCodeTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringPathTrait;


class OwoDriveUploadfile implements OwoDriveUploadfileInterface
{
    use OwoTakeStringNameTrait;
    use OwoTakeIntegerCodeTrait;
    use OwoTakeStringPathTrait;

    public function __construct(string $path, int $code = 0)
    {
        $this->setFromPath($path)->setCode($code);
    }

    public function setFromPath(string $path): self
    {
        return $this->setPath($path)->setNameFromPath();
    }

    public function setNameFromPath(): self
    {
        return $this->setName(OwoHelperPath::basename($this->path));
    }

    public function size(): ?int
    {
        if (true === $this->wasUploadedProperly()) {
            return \filesize($this->path) ?: bull;
        }
        return null;
    }

    public function mimect(): ?string
    {
        if (true === $this->wasUploadedProperly()) {
            return \mime_content_type($this->path) ?: null;
        }
        return null;
    }

    public function move(string $destination): bool
    {
        return (true === \move_uploaded_file($this->path, $destination));
    }

    public function wasUploadedProperly(): bool
    {
        return (true === \is_uploaded_file($this->path));
    }
}
