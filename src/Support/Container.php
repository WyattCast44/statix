<?php

namespace Statix\Support;

use Illuminate\Container\Container as BaseContainer;

class Container extends BaseContainer
{
    /**
     * @return bool
     *
     * @see \Illuminate\Contracts\Foundation\Application::isDownForMaintenance()
     */
    public function isDownForMaintenance(): bool
    {
        return false;
    }

    /**
     * @param string|string[] $environments
     * @return string|bool
     *
     * @see \Illuminate\Contracts\Foundation\Application::environment()
     */
    public function environment(...$environments)
    {
        if(empty($environments)) {
            return 'statix';
        }

        return in_array(
            'statix',
            is_array($environments[0]) ? $environments[0] : $environments
        );
    }

    /**
     * @return string
     *
     * @see \Illuminate\Contracts\Foundation\Application::getNamespace()
     */
    public function getNamespace(): string
    {
        return 'App\\';
    }
}