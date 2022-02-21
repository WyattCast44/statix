<?php

namespace Statix\Events;

class LocaleUpdated
{
    public function __construct(public string $locale) {}
}
