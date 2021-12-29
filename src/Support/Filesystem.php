<?php

namespace Statix\Support;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;

class Filesystem
{
    public static function new()
    {
        return new BaseFilesystem;
    }

    public static function ensureDirectoryExists($path, $mode = 0777, $recursive = true): void
    {
        static::new()->ensureDirectoryExists($path, $mode, $recursive);
    }

    public static function put($path, $contents, $lock = false)
    {
        return static::new()->put($path, $contents, $lock);
    }

    public static function get($path, $lock = false)
    {
        return static::new()->get($path, $lock);
    }
}