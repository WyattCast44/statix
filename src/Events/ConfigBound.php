<?php

namespace Statix\Events;

use Illuminate\Config\Repository;

class ConfigBound
{
    public function __construct(public Repository $config) {}
}
