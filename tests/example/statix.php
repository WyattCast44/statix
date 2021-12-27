<?php

use Statix\Application;

require_once './../../vendor/autoload.php';

$application = Application::new();

$application->cli->run();