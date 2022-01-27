<?php

use Statix\Application;

require_once './../../vendor/autoload.php';

$app = Application::new();

// dd(app()->make('statix'));

$app->cli->run();
