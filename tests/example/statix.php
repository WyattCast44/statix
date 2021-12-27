<?php

use Statix\Application;
use Statix\Routing\Route;

require_once './../../vendor/autoload.php';

$application = Application::new();

$application->cli->run();