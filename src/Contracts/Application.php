<?php

namespace Statix\Contracts;

use Illuminate\Contracts\Container\Container;

interface Application extends Container
{
    public function version(): string;

    public function appPath(string $path = ''): string;

    public function basePath(string $path = ''): string;

    public function configPath(string $path = ''): string;

    public function databasePath(string $path = ''): string;

    public function langPath(string $path = ''): string;

    public function publicPath(string $path = ''): string;

    public function resourcePath(string $path = ''): string;

    public function storagePath(string $path = ''): string;

    public function viewPath(string $path = ''): string;

    public function environment(string|array ...$environments): string|bool;

    public function runningInConsole(): bool;

    public function runningUnitTests(): bool;

    public function getLocale(): string;

    public function setLocale(string $locale): self;

    public function isLocal(): bool;

    public function isProduction(): bool;
}
