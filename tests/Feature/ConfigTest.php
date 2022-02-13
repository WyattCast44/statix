<?php 

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Config\Repository;

class ConfigTest extends TestCase
{
    public function test_user_can_access_config_from_helper()
    {
        $this->assertInstanceOf(Repository::class, config());
    }

    public function test_user_can_access_config_from_container()
    {
        $this->assertInstanceOf(Repository::class, app('config'));
    }

    public function test_user_can_access_config_on_app_instance()
    {
        $this->assertInstanceOf(Repository::class, $this->app->config);
    }
    
    public function test_user_can_access_hardcoded_config_values_from_helper()
    {
        $this->assertEquals('bar', config('site.foo'));
    }

    public function test_user_can_access_config_values_loaded_from_env_from_helper()
    {
        $this->assertEquals('baz', config('site.bar'));
    }

    public function test_user_can_override_config_values_at_runtime()
    {
        config()->set('site.bar', 'statix');

        $this->assertEquals('statix', config('site.bar'));
    }
}