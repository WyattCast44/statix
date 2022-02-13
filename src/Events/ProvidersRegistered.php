<?php

namespace Statix\Events;

use Illuminate\Support\Collection;

class ProvidersRegistered
{
    public function __construct(public Collection $providers) {}
}
