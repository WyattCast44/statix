<?php

use Statix\Events\PathsRegistered;
use Statix\Events\ProvidersBooted;
use Illuminate\Support\Facades\Event;
use Statix\Events\PathsBound;

Event::listen(function(PathsBound $event) {
    // dd($event);
});