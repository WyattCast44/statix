<?php

use Illuminate\Support\Facades\Event;
use Statix\Events\DefaultProvidersBooted;

Event::listen(function(DefaultProvidersBooted $event) {
    // dd($event);
});