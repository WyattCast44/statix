<?php

use Statix\Application;
use Illuminate\Support\Facades\Route;

require_once './../../vendor/autoload.php';

$app = Application::new();

dd(Route::getRoutes());

$app->cli->run();
