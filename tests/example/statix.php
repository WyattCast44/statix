<?php

use Statix\Application;

require_once './../../vendor/autoload.php';

$application = Application::new();

// dd(route('welcome'));

$application->cli->run();