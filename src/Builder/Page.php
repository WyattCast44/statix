<?php

namespace Statix\Builder;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Blade;

class Page
{
    protected $path; 

    protected $content; 

    private $body;

    private $matter;

    private string $uri;

    public function __construct(string $path, $contents) 
    {    
        $this->path = $path;

        $this->setContent($contents);

        $this
            ->parseContents()
            ->determineUri();
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    private function parseContents()
    {
        $pattern = '/^[\s\r\n]?---[\s\r\n]?$/sm';

        $parts = preg_split($pattern, PHP_EOL.ltrim($this->content));

        if (count($parts) < 3) {
            $this->body = $this->content;

            return $this;
        } 

        $this->matter = Yaml::parse(Blade::render(trim($parts[1])));

        if($this->matter != null) {
            foreach ($this->matter as $key => $value) {
                if(Str::contains($key, ':')) {

                    switch (Str::after($key, ':')) {
                        case 'date':
                            $this->matter[$key] = Carbon::parse($value);
                            break;

                        case 'bool':
                            $this->matter[$key] = (bool) $value;
                            break;
                        
                        default:
                            break;
                    }

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

        $this->uri = Str::replace('.blade', '', $this->uri);

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
        return $this->uri;
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

    public function shouldPublish(callable $callback): bool
    {
        return $callback($this);
    }
}