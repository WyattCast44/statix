<?php

namespace Statix\Events;

use Illuminate\Config\Repository;
use Illuminate\Console\Application;

class PathsRegistered
{
    public function __construct(public Repository $paths) {}
}
