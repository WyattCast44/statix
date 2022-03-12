<?php

namespace Statix\Builder;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Blade;

class Page
{
    private $body;

    private $matter;

    private string $uri;

    public function __construct(
        private string $path,
        private $contents,
    ) {
        $this
            ->parseContents()
            ->determineUri();
    }

    private function parseContents()
    {
        $pattern = '/^[\s\r\n]?---[\s\r\n]?$/sm';

        $parts = preg_split($pattern, PHP_EOL.ltrim($this->contents));

        if (count($parts) < 3) {
            $this->body = $this->contents;

            return $this;
        } 

        $this->matter = Yaml::parse(Blade::render(trim($parts[1])));

        if($this->matter != null) {
            foreach ($this->matter as $key => $value) {
                if(is_int($value)) {
                    $this->matter[$key] = Carbon::parse($value);
                }
            }
        }

        $this->body = Blade::render(implode(PHP_EOL.'---'.PHP_EOL, array_slice($parts, 2)), [
            'page' => $this,
        ]);
 
        return $this;
    }

    private function determineUri() 
    {
        $this->uri = 
            Str::replace(realpath(resource_path('content')), "", dirname(realpath($this->getPath())))
            . DIRECTORY_SEPARATOR .  pathinfo($this->getPath(), PATHINFO_FILENAME);


        return $this;
    }

    public function matter(string $key = null, $default = null)
    {
        if ($key) {
            return Arr::get($this->matter, $key, $default);
        }

        return $this->matter;
    }

    public function hasFrontMatter(): bool
    {
        return $this->matter === null;
    }

    public function getPath(): string 
    {
        return realpath($this->path);
    }

    public function getUri(): string 
    {
        //
    }

    public function getUrl(): string
    {
        return $this->getPath();
    }

    public function getFilename(): string
    {
        return pathinfo($this->getPath(), PATHINFO_FILENAME);
    }

    public function getExtension(): string
    {
        return pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    public function getModifiedTime(): Carbon
    {
        return ($time = filemtime($this->getPath())) ? Carbon::parse($time) : Carbon::now();
    }

    public function shouldPublish(callable $cb): bool
    {
        return $cb($this);
    }
}