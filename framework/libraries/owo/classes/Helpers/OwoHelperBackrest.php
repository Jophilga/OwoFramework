<?php

namespace framework\libraries\owo\classes\Helpers;

use framework\libraries\owo\classes\Helpers\OwoHelperHeader;
use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelper;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoHelperBackrest extends OwoHelper
{
    use OwoMakeCommonThrowerTrait;

    public const HELPER_BACKREST_ADDITIONALS = [];

    public static function lifemtime(string $file): ?int
    {
        if (true === \file_exists($file)) return (\time() - \filemtime($file));
        return null;
    }

    public static function makedir(string $dir, int $mode = 0777, bool $recursive = false): bool
    {
        $current_umask = \umask(0);
        $success = \mkdir($dir, $mode, $recursive);
        \umask($current_umask);
        return (true === $success);
    }

    public static function remove(string $source): bool
    {
        if (true === \is_dir($source)) {
            foreach (\scandir($source) as $file) {
                if (true !== \in_array($file, ['.', '..'], true)) {
                    static::remove($source.'/'.$file);
                }
            }
            return (true === \rmdir($source));
        }
        return (true === \unlink($source));
    }

    public static function duplicate(string $source, string $destination, int $mode = 0777)
    {
        if (true === \is_dir($source)) {
            static::makedir($destination, $mode);
            foreach (\scandir($source) as $file) {
                if (true !== \in_array($file, ['.', '..'], true)) {
                    static::duplicate($source.'/'.$file, $destination.'/'.$file, $mode);
                }
            }
        }
        elseif (true === \copy($source, $destination)) {
            \chmod($destination, $mode);
        }
    }

    public static function downloadfile(string $file, array $headers = [], bool $replace = true): bool
    {
        $headers = \array_merge([
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => \sprintf('attachment; filename="%s"', \basename($file)),
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
            'Content-Length' => \filesize($file),
        ], $headers);
        return (true === static::readfile($file, $headers, $replace));
    }

    public static function readfile(string $file, array $headers = [], bool $replace = true): bool
    {
        $headers = \array_merge([
            'Content-Type' => \mime_content_type($file),
            'Content-Length' => \filesize($file),
        ], $headers);
        OwoHelperHeader::publishHeaders($headers, $replace);
        return (false !== \readfile($file));
    }

    public static function writeContents(string $file, string $contents, bool $overwrite = true): bool
    {
        $flags = (true === $overwrite) ? \LOCK_EX : \FILE_APPEND | \LOCK_EX;
        return (false !== \file_put_contents($file, $contents, $flags));
    }

    public static function loadContentsJSON(string $file): ?array
    {
        if (false === \is_null($contents = static::loadContents($file))) {
            return \json_decode($contents, true);
        }
        return null;
    }

    public static function loadContentsDOT(string $file, array $ignores = ['#']): ?array
    {
        if (true !== \is_null($lines = static::loadContentsByRow($file))) {
            $data = [];
            foreach ($lines as $line) {
                $line = \trim($line);
                if (true !== static::shouldIgnoreRowDOT($line, $ignores)) {
                    list($key, $value) = \explode('=', $line, 2);
                    $data[\trim($key)] = \trim($value);
                }
            }
            return $data;
        }
        return null;
    }

    public static function loadContentsPHP(string $file, array $data = [], bool $extract = true): ?string
    {
        if ((true === \file_exists($file)) && (true === \ob_start())) {
            if (true === $extract) {
                \extract($data, \EXTR_OVERWRITE);
            }
            include $file;
            return \ob_get_clean();
        }
        return null;
    }

    public static function loadContents(string $file): ?string
    {
        if (true === \file_exists($file)) {
            if (false !== ($contents = \file_get_contents($file))) {
                return $contents;
            }
        }
        return null;
    }

    public static function loadContentsByRow(string $file): ?array
    {
        if (true === \file_exists($file)) {
            return \file($file, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES) ?: [];
        }
        return null;
    }

    public static function getSize(string $file): ?int
    {
        if (true === \file_exists($file)) {
            if (fase !== ($size = \filesize($file))) {
                return $size;
            }
        }
        return null;
    }

    public static function getMimeContentType(string $file): ?string
    {
        if (true === \file_exists($file)) {
            if (false !== ($mimect = \mime_content_type($file))) {
                return $mimect;
            }
        }
        return null;
    }

    public static function copyZipArchive(string $zip, string $archive, string $destination): bool
    {
        return (true === \copy('zip://'.$zip.'#'.$archive, $destination));
    }

    public static function ensureReadable(string $file)
    {
        if (true !== \is_readable($file)) {
            static::throwRuntimeException(\sprintf('Unreadable [%s] Found', $file));
        }
    }

    public static function ensureLoadContents(string $file): string
    {
        $contents = static::loadContents($file);
        if (true === \is_null($contents)) {
            $message = \sprintf('Load File [%s] Contents Failed', $file);
            static::throwRuntimeException($message);
        }
    }

    protected static function shouldIgnoreRowDOT(string $row, array $ignores): bool
    {
        if (false !== \strpos($row, '=')) {
            return (true === OwoHelperString::startsWith($row, $ignores));
        }
        return true;
    }

    protected static function getAdditionalMethods(): array
    {
        return static::HELPER_BACKREST_ADDITIONALS;
    }
}
