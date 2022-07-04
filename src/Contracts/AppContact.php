<?php

namespace Statix\Contracts;

use Illuminate\Contracts\Container\Container;

interface AppContract extends Container
{
    public function version(): string;

    public function getLocale(): string;

    public function setLocale(string $locale): self;
}
