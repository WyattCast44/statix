<?php

namespace Statix\Support;

use Exception;

class PathRepository
{
    protected $paths = [];

    /**
     * Get a path
     *
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        if (array_key_exists($key, $this->paths)) {
            return $this->paths[$key];
        }

        return $default;
    }

    /**
     * Set a path
     *
     * @return self
     */
    public function set(string $key, $path, $overide = true): self
    {
        if (!array_key_exists($key, $this->paths)) {
            $this->paths[$key] = $path;

            return $this;
        }

        if ($overide) {
            $this->paths[$key] = $path;

            return $this;
        }

        throw new Exception("Cannot overide path key: $key, currently set to: " . $this->paths[$key]);
    }

    /**
     * Check if path exists
     * 
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->paths);
    }

    public function append($key, ...$appends): string
    {
        if($this->has($key)) {
            
            if(is_null($appends)) {
                return $this->get($key);
            }
            
            return rtrim($this->get($key), '/') . '/' . implode('/', $appends);
        }

        return $appends;
    }
}
