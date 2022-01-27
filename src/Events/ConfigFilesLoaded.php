<?php

namespace Statix\Events;

use Illuminate\Config\Repository;

class ConfigFilesLoaded
{
    public function __construct(public Repository $config) {}
}
