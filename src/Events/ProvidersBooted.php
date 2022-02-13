<?php

namespace Statix\Events;

use Illuminate\Support\Collection;

class ProvidersBooted
{
    public function __construct(public Collection $providers) {}
}
