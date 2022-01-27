<?php

use Illuminate\Support\Facades\Event;
use Statix\Events\CliBound;
use Statix\Events\CliCommandsRegistered;
use Statix\Events\ConfigFilesLoaded;

Event::listen(function(CliCommandsRegistered $event) {
    // dd($event);
});