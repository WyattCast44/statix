<?php

use Statix\Events\ProvidersBooted;
use Illuminate\Support\Facades\Event;
use Statix\Events\DefaultProvidersBooted;
use Illuminate\Console\Events\ArtisanStarting;

Event::listen(function(ArtisanStarting $event) {
    // dd($event);
});