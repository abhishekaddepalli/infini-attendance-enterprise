<?php

namespace Infini\Attendance\Services;

class PwaService
{
    public function getManifest(): array
    {
        return [
            'name' => 'Infini Attendance',
            'short_name' => 'Infini',
            'start_url' => '/',
            'display' => 'standalone'
        ];
    }
}
