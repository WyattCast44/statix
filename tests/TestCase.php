<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Statix\Application;

abstract class TestCase extends BaseTestCase
{
    public Application $app;
    
    public function setUp(): void
    {
        chdir(__DIR__ . '/example-app');

        $this->app = Application::new();
    }
}
