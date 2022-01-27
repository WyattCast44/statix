<?php

namespace Statix\Events;

use Illuminate\Config\Repository;

class PathsBound
{
    public function __construct(public Repository $paths) {}
}
