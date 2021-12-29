<?php

use Statix\Application;
use Symfony\Component\Finder\Finder;
use Spatie\YamlFrontMatter\YamlFrontMatter;

require_once './../../vendor/autoload.php';

$app = Application::new();

$app->cli->run();