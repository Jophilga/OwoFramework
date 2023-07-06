<?php

namespace framework\libraries\owo\classes\Codes;

use framework\libraries\owo\classes\Codes\OwoCodeCompiler;
use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Codes\OwoCodeTemplatorPHPInterface;
use framework\libraries\owo\interfaces\Caches\OwoCacheDirectoryInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoCodeTemplatorPHP extends OwoCodeCompiler implements OwoCodeTemplatorPHPInterface
{
    use OwoMakeCommonThrowerTrait;

    protected $manager = null;
    protected $blocks = [];

    public const CODE_TEMPLATOR_PHP_PATTERNS = [
        'returns' => '/{%\s*(returns?)\s+(.+?)\s*%}/is',
        'blocks' => '/{%\s*block\s+(\w+?)\s*%}(.*?){%\s*endblock\s*(.*?)\s*%}/is',
        'yields' => '/{%\s*(yield)\s+(\w+?)\s*%}/is',
        'includes' => '/{%\s*(extends?|includes?)\s+(.+?)\s*%}/is',
    ];

    public function __construct(OwoCacheDirectoryInterface $manager, bool $clean = true)
    {
        $this->setCacheManager($manager)->setClean($clean);
    }

    public function setCacheManager(OwoCacheDirectoryInterface $manager): self
    {
        $this->manager = $manager;
        return $this;
    }

    public function getCacheManager(): ?OwoCacheDirectoryInterface
    {
        return $this->manager;
    }

    public function compile(string $code): string
    {
        return static::affixCodeMentionPHP($this->compileCodeToPHP($code));
    }

    public function compileCodeToPHP(string $code): string
    {
        $this->resetBlocks();
        $code = $this->compileCodeIncludes($this->compileCodeReturns($code));
        $code = $this->compileCodeYields($this->compileCodeBlocks($code));
        $code = $this->compileCodePrints($this->compileCodeSafePrints($code));
        $code = $this->compileCodeCloseTags($code);
        return $code;
    }

    public function resetBlocks(): self
    {
        $this->blocks = [];
        return $this;
    }

    public function changeDir(string $dir): self
    {
        $this->manager->setDir($dir);
        return $this;
    }

    public function dir(): string
    {
        return $this->manager->getDir();
    }

    public function clearTemplates(string $pattern = '.*'): self
    {
        $this->manager->deleteAll($pattern);
        return $this;
    }

    public function render(string $file, array $data = [], bool $refresh = false): ?string
    {
        if (true === $refresh) {
            if (true !== $this->template($file, true)) {
                $message = \sprintf('Refresh Template [%s] Contents Failed', $file);
                static::throwRuntimeException($message);
            }
        }

        if ((true === $this->hasTemplate($file, $template)) || (true === $this->template($file, false))) {
            return OwoHelperBackrest::loadContentsPHP($template, $data, true);
        }
        return null;
    }

    public function template(string $file, bool $overwrite = true): bool
    {
        if (true !== \is_null($code = $this->transpose($file))) {
            return (true === $this->writeInCache($file, $code, $overwrite));
        }
        return false;
    }

    public function digest(string $name, string $code, bool $overwrite = true): bool
    {
        return (true === $this->writeInCache($name, $this->compile($code), $overwrite));
    }

    public function hasTemplate(string $name, string &$template = null): bool
    {
        if (true === $this->manager->exists($name, $template)) {
            return (true === $this->manager->approves($template));
        }
        return false;
    }

    protected function writeInCache(string $name, string $contents, bool $overwrite = true): bool
    {
        return (true === $this->manager->write($name, $contents, $overwrite));
    }

    protected function compileCodeReturns(string $code, array $args = []): string
    {
        $pattern = static::CODE_TEMPLATOR_PHP_PATTERNS['returns'];
        return $this->translate($code, $pattern, function (array $matches) use ($args) {
            return static::expressionCallArgs($matches[2], $args) ?? $matches[0];
        });
    }

    protected function compileCodeIncludes(string $code): string
    {
        $pattern = static::CODE_TEMPLATOR_PHP_PATTERNS['includes'];
        return $this->translate($code, $pattern, function (array $matches) {
            return $this->includes($matches[2]);
        });
    }

    protected function compileCodeBlocks(string $code): string
    {
        $pattern = static::CODE_TEMPLATOR_PHP_PATTERNS['blocks'];
        return $this->translate($code, $pattern, function (array $matches) {

            $inner = $matches[2] ?? static::CODE_COMPILER_CLEAN_TRANSLATION;
            if (true !== empty($matches[3])) {
                $inner = static::expressionCallArgs($matches[3], [$inner]);
            }
            $this->setParentBlocks($matches[1], $inner);
            return $matches[0];
        });
    }

    protected function compileCodeYields(string $code): string
    {
        $pattern = static::CODE_TEMPLATOR_PHP_PATTERNS['yields'];
        return $this->translate($code, $pattern, function (array $matches) {
            return $this->blocks[$matches[2]] ?? $matches[0];
        });
    }

    protected function compileCodeCloseTags(string $code): string
    {
        return $this->translate($code, '/{%\s*(.+?)\s*%}/is', '<?php $1; ?>');
    }

    protected function compileCodeSafePrints(string $code): string
    {
        $translation = "<?php echo \htmlentities($1, \ENT_QUOTES, 'UTF-8'); ?>";
        return $this->translate($code, '/{{{\s*(.+?)\s*}}}/is', $translation);
    }

    protected function compileCodePrints(string $code): string
    {
        return $this->translate($code, '/{{\s*(.+?)\s*}}/is', '<?php echo $1; ?>');
    }

    protected function setParentBlocks(string $name, string $inner): self
    {
        $this->blocks[$name] = $this->blocks[$name] ?? $inner;
        if (false !== \strpos($inner, '@parent')) {
            $this->blocks[$name] = \str_replace('@parent', $this->blocks[$name], $inner);
        }
        return $this;
    }

    protected function includes(string $file): string
    {
        if (true === \is_null($code = OwoHelperBackrest::loadContents($file))) {
            static::throwRuntimeException(\sprintf('Load File [%s] Contents Failed', $file));
        }
        return $this->compileCodeIncludes($code);
    }

    public static function expressionCallArgs(string $expression, array $args = []): ?string
    {
        $matches = \explode('|', $expression);
        if (true !== \is_callable($callback = \array_shift($matches))) {
            static::throwRuntimeException(\sprintf('Callable [%s] Not Found', $callback));
        }

        $outcome = \call_user_func_array($callback, \array_merge($matches, $args));
        return (true === \is_string($outcome)) ? $outcome : null;
    }

    public static function affixCodeMentionPHP(string $code): string
    {
        return \sprintf('%s%s%s%s', static::getCodeMentionPHP(), \PHP_EOL, \PHP_EOL, $code);
    }

    public static function getCodeMentionPHP(): string
    {
        $format = ("<?php class_exists('%s') or exit('No Template Usage'); ?>");
        return \sprintf($format, static::class);
    }
}
