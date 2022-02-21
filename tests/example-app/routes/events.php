<?php

use Statix\Events\PathsRegistered;
use Statix\Events\ProvidersBooted;
use Illuminate\Support\Facades\Event;

Event::listen(function(PathsRegistered $event) {
    // dd($event);
});