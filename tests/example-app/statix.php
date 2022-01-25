<?php

use Statix\Application;

require_once './../../vendor/autoload.php';

$app = Application::new();

// dd(config_path('app.php'));

$app->cli->run();
