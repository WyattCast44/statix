<?php

use Statix\Events\ProvidersBooted;
use Illuminate\Support\Facades\Event;

Event::listen(function(ProvidersBooted $event) {
    // dd($event);
});