<?php

namespace Tests\Unit;

use Tests\TestCase;
use Statix\Application;

class ApplicationTest extends TestCase
{
    public function test_application_has_static_new()
    {
        $this->assertTrue(method_exists(Application::class, 'new'));
    }

    public function test_static_new_returns_instance()
    {
        $this->assertInstanceOf(
            Application::class, 
            Application::new(__DIR__ . '/../example-app')
        );
    }
}