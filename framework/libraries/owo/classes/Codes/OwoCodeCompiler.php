<?php

namespace framework\libraries\owo\classes\Codes;

use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;

use framework\libraries\owo\interfaces\Codes\OwoCodeCompilerInterface;

use framework\libraries\owo\traits\Takes\Booleans\OwoTakeBooleanCleanTrait;


abstract class OwoCodeCompiler implements OwoCodeCompilerInterface
{
    use OwoTakeBooleanCleanTrait;

    public const CODE_COMPILER_CLEAN_TRANSLATION = '';

    public function __construct(bool $clean = true)
    {
        $this->setClean($clean);
    }

    public function transpose(string $file): ?string
    {
        if (true !== \is_null($code = OwoHelperBackrest::loadContents($file))) {
            return $this->compile($code);
        }
        return null;
    }

    public function translate(string $code, string $pattern, $translation): string
    {
        $code = static::alterCode($code, $pattern, $translation);
        if (true === $this->clean) $code = static::cleanCode($code, $pattern);
        return $code;
    }

    public static function cleanCode(string $code, string $pattern): string
    {
        return static::alterCode($code, $pattern, static::CODE_COMPILER_CLEAN_TRANSLATION);
    }

    public static function alterCode(string $code, string $pattern, $translation): string
    {
        if (true === \is_callable($translation)) {
            return \preg_replace_callback($pattern, $translation, $code);
        }
        elseif (true === \is_string($translation)) {
            return \preg_replace($pattern, $translation, $code);
        }
        return $code;
    }

    public static function getCodeMatches(string $code, string $pattern): array
    {
        if (false !== \preg_match_all($pattern, $code, $matches, \PREG_SET_ORDER)) {
            return $matches;
        }
        return [];
    }

    abstract public function compile(string $code): string;
}
