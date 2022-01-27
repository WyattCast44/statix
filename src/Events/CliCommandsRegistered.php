<?php

namespace Statix\Events;

use Illuminate\Console\Application;

class CliCommandsRegistered
{
    public function __construct(public Application $cli) {}
}
