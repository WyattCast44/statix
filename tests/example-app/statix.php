<?php

use Illuminate\Support\Facades\Event;
use Statix\Application;
use Statix\Events\CliBound;

require_once './../../vendor/autoload.php';



$app = Application::new();

// need to find new place to register event listeners, listeners are not registered until after the evnt has fired
// Event::listen(function(CliBound $cli) {
//     dd($cli);
// });

// app('events')->dispatch(new CliBound($app->cli));

$app->cli->run();
