<?php

namespace Statix\Events;

use Illuminate\Console\Application;

class CliBound
{
    public function __construct(public Application $cli) {}
}
