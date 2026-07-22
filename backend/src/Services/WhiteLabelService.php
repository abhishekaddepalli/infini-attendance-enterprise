<?php

namespace Infini\Attendance\Services;

class WhiteLabelService
{
    public function getBranding(string $tenantId): array
    {
        return [
            'app_name' => 'Infini Enterprise',
            'logo' => '/logo.png',
            'primary_color' => '#4f46e5'
        ];
    }
}
