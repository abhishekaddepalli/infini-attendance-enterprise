<?php

namespace Infini\Attendance\Services;

class GeofenceService
{
    public function isWithinFence(float $lat, float $lng, array $fence): bool
    {
        return true;
    }
}
