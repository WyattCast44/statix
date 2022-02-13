<?php

namespace Statix\Events;

use Illuminate\Support\Collection;

class DefaultProvidersBooted
{
    public function __construct(public Collection $providers) {}
}
