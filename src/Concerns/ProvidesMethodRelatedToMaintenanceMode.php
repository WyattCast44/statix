<?php

namespace Statix\Concerns;

use Statix\Exceptions\NotImplementedException;

trait ProvidesMethodRelatedToMaintenanceMode
{
    public function maintenanceMode()
    {
        throw new NotImplementedException('This feature is not implemented in statix.');
    }

    public function isDownForMaintenance()
    {
        throw new NotImplementedException('This feature is not implemented in statix.');
    }
}