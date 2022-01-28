<?php

namespace Statix\Events;

use Illuminate\Console\Application;

class DefaultCliCommandsRegistered
{
    public function __construct(public Application $cli) {}
}
