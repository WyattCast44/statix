<?php

namespace Statix\Builder;

use Illuminate\Support\Carbon;
use Symfony\Component\Yaml\Yaml;
use Spatie\YamlFrontMatter\Document;
use Illuminate\Support\Facades\Blade;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Page
{
    private Document $document;

    public function __construct(
        private string $path,
        private $contents,
    ) {
        $this->document = $this->transformContentsToDocument();
        
        // // array_push($posts, ['body' => $object->body()], $object->matter());
    }

    public function transformContentsToDocument()
    {
        $pattern = '/^[\s\r\n]?---[\s\r\n]?$/sm';

        $parts = preg_split($pattern, PHP_EOL.ltrim($this->contents));

        if (count($parts) < 3) {
            return new Document([], $this->contents);
        }

        $matter = Yaml::parse(Blade::render(trim($parts[1])));

        $body = implode(PHP_EOL.'---'.PHP_EOL, array_slice($parts, 2));

        return new Document($matter, $body);
    }

    public function hasFrontMatter(): bool
    {
        return $this->document->matter === [];
    }

    public function getPath(): string 
    {
        return realpath($this->path);
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
}