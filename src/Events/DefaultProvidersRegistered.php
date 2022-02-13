<?php

namespace Statix\Events;

use Illuminate\Support\Collection;

class DefaultProvidersRegistered
{
    public function __construct(public Collection $providers) {}
}
