<?php

namespace Tests;

use Statix\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Application $app;
    
    public function setUp(): void
    {
        chdir(__DIR__ . '/example-app');

        $this->app = Application::new();
    }

    public function useExampleApp(): Application
    {
        return Application::new(__DIR__ . '/example-app');
    }
}
