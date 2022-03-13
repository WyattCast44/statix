<?php 

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Config\Repository;

class ConfigTest extends TestCase
{
    public function test_user_can_access_config_from_helper()
    {
        $this
            ->useExampleApp()
            ->assertInstanceOf(Repository::class, config());
    }

    public function test_user_can_access_config_from_container()
    {
        $this
            ->useExampleApp()
            ->assertInstanceOf(Repository::class, app('config'));
    }
    
    public function test_user_can_access_hardcoded_config_values_from_helper()
    {
        $this
            ->useExampleApp()
            ->assertEquals('bar', config('site.foo'));
    }

    public function test_user_can_access_config_values_loaded_from_env_from_helper()
    {
        $this
            ->useExampleApp()
            ->assertEquals('baz', config('site.bar'));
    }

    public function test_user_can_override_config_values_at_runtime()
    {
        $this->useExampleApp();
        
        config()->set('site.bar', 'statix');

        $this->assertEquals('statix', config('site.bar'));
    }
}