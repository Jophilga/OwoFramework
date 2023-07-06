<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoHelperCoder extends OwoHelper
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_CODER_ADDITIONALS = [];

    public static function scriptTagPHP(string $code): string
    {
        return \sprintf('<?php %s ?>', $code);
    }

    public static function commentTagHtml(string $coment): string
    {
        return \sprintf('<!-- %s -->', $comment);
    }

    public static function doubleTagHtml(string $name, $inner, array $params = []): string
    {
        list($attr, $inner) = [ static::attrTagHtml($params), static::innerTagHtml($inner), ];
        return \sprintf('<%s %s>%s</%s>', $name, $attr, $inner, $name);
    }

    public static function simpleTagHtml(string $name, array $params = []): string
    {
        return \sprintf('<%s %s />', $name, static::attrTagHtml($params));
    }

    public static function injectOnFirstTagHtml(string $html, string $code, string $tag): string
    {
        if (false !== ($pos = \stripos($html, $tag))) {
            return \sprintf('%s%s%s', \substr($html, 0, $pos), $code, \substr($html, $pos));
        }
        return $html;
    }

    public static function injectOnLastTagHtml(string $html, string $code, string $tag): string
    {
        if (false !== ($pos = \strripos($html, $tag))) {
            return \sprintf('%s%s%s', \substr($html, 0, $pos), $code, \substr($html, $pos));
        }
        return $html;
    }

    public static function innerTagHtml($inner): string
    {
        if (true === \is_string($inner)) return $inner;
        elseif (true === \is_array($inner)) {
            return \implode(\PHP_EOL, \array_map([static::class, 'innerTagHtml'], $inner));
        }
        elseif (true === \is_callable($inner)) {
            if (true !== \is_string($outcome = \call_user_func($inner))) {
                static::throwRuntimeException('Not String Ouptcome Callable Found');
            }
            return $outcome;
        }

        $message = \sprintf('Bad Inner Type [%s] Found', \gettype($inner));
        static::throwRuntimeException($message);
    }

    public static function attrTagHtml(array $params): string
    {
        $sections = [];
        foreach ($params as $key => $value) {
            if (true === \is_string($key)) {
                $sections[] = \sprintf('%s="%s"', $key, \strval($value));
            }
            else $sections[] = \strval($value);
        }
        return \implode(' ', $sections);
    }

    public static function simpleTemplateHtml(array $maps, array $blocks = []): string
    {
        $sections = [];
        foreach ($maps as $name => $map) {
            if (true === \is_array($map)) {
                $sections[] = static::simpleTemplateHtml($map, $blocks);
            }
            elseif (true === \is_string($map)) {
                $sections[] = \strval($blocks[$name] ?? null);
            }
        }
        return \implode(\PHP_EOL, $sections);
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_CODER_ADDITIONALS;
    }
}
