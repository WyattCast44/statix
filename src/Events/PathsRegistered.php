<?php

namespace Statix\Events;

use Illuminate\Config\Repository;

class PathsRegistered
{
    public function __construct(public Repository $paths) {}
}
