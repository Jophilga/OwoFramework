<?php

namespace framework\libraries\owo\interfaces\Codes;

use framework\libraries\owo\interfaces\Caches\OwoCacheDirectoryInterface;
use framework\libraries\owo\interfaces\Codes\OwoCodeCompilerInterface;


interface OwoCodeTemplatorPHPInterface extends OwoCodeCompilerInterface
{
    public function setCacheManager(OwoCacheDirectoryInterface $manager): self;

    public function getCacheManager(): ?OwoCacheDirectoryInterface;

    public function compileCodeToPHP(string $code): string;

    public function resetBlocks(): self;

    public function clearTemplates(string $pattern = '.*'): self;

    public function render(string $file, array $data = [], bool $refresh = false): ?string;

    public function template(string $file, bool $overwrite = true): bool;

    public function digest(string $name, string $code, bool $overwrite = true): bool;

    public function hasTemplate(string $name, string &$template = null): bool;

    public function dir(): string;

    public function changeDir(string $dir): self;
}
