<?php

use Illuminate\Support\Env;
use Statix\Events\EnvFileLoaded;
use Illuminate\Support\Facades\Event;

Event::listen(function(EnvFileLoaded $event) {
    //
});