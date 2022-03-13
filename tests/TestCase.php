<?php

namespace Tests;

use Statix\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Application $app;
    
    public function useExampleApp(): self
    {
        $app = Application::new(__DIR__ . '/example-app');

        return $this;
    }
}
