<?php

namespace framework\libraries\owo\classes\Caches;

use framework\libraries\owo\interfaces\Caches\OwoCacheEntityInterface;

use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerUatTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringContentTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringNameTrait;


class OwoCacheEntity implements OwoCacheEntityInterface
{
    use OwoTakeIntegerUatTrait;
    use OwoTakeStringContentTrait;
    use OwoTakeStringNameTrait;

    public function __construct(string $name, string $content, int $uat = 0)
    {
        $this->setName($name)->updateContentAt($content, $uat);
    }

    public function updateContentNow(string $content): self
    {
        return $this->updateContentAt($content, time());
    }

    public function usable(int $duration): bool
    {
        return ($this->lifemtime() < $duration);
    }

    public function lifemtime(): int
    {
        return (\time() - $this->uat);
    }

    protected function setUat(int $uat): self
    {
        $this->uat = $uat;
        return $this;
    }

    protected function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    protected function updateContentAt(string $content, int $uat): self
    {
        return $this->setContent($content)->setUat($uat);
    }
}
