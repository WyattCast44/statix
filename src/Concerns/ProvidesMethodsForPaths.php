<?php

namespace Statix\Concerns;

use Statix\Exceptions\NotImplementedException;

trait ProvidesMethodsForPaths
{
    /**
     * Get the path to the app directory
     *
     * @param  string  $path
     */
    public function path($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app'($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the base path of the installation.
     *
     * @param  string  $path
     */
    public function basePath($path = ''): string
    {
        return $this->basePath.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @param  string  $path
     * @return string
     */
    public function bootstrapPath($path = '')
    {
        throw new NotImplementedException('This feature is not implemented in statix.');
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     */
    public function configPath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the database directory.
     *
     * @param  string  $path
     */
    public function databasePath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'database'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     */
    public function resourcePath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the storage directory.
     *
     * @param  string  $path
     * @return string
     */
    public function storagePath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'storage'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }
}