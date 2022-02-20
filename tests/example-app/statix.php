<?php

use Statix\Actions\BuildRouteTreeFromFileStructrure;
use Statix\Application;
use Statix\Builder\Page;

require_once './../../vendor/autoload.php';

$app = Application::new();

// app(BuildRouteTreeFromFilConsoleApplicationeStructrure::class)->execute();

$app->cli->run();