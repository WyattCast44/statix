<?php

use Statix\Application;

require_once './../../vendor/autoload.php';

$app = Application::new();

$app->cli->run();
