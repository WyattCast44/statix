<?php

namespace Tests\Unit;

use Tests\TestCase;
use Statix\Application;
use Statix\Support\Container;
use Illuminate\Config\Repository;

class ApplicationTest extends TestCase
{
    public function test_application_has_static_new()
    {
        $this->assertTrue(method_exists(Application::class, 'new'));
    }

    public function test_static_new_returns_instance()
    {
        $this->assertInstanceOf(Application::class, Application::new());
    }

    public function test_has_a_public_instance_of_container()
    {
        $this->assertInstanceOf(Container::class, Application::new()->container);
    }

    public function test_has_a_public_instance_of_paths()
    {
        $this->assertInstanceOf(Repository::class, Application::new()->paths);
    }

    public function test_has_a_public_instance_of_config()
    {
        $this->assertInstanceOf(Repository::class, Application::new()->config);
    }
}