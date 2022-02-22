<?php

use Statix\Application;

require_once 'vendor/autoload.php';

$app = Application::new(__DIR__ . '/tests/example-app');

$app->cli->run();