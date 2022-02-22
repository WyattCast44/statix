<?php

use Statix\Events\EnvFileLoaded;
use Illuminate\Support\Facades\Event;

Event::listen(function(EnvFileLoaded $event) {
    // dd($event);
});