<?php

if (!function_exists('infini_version')) {
    function infini_version(): string
    {
        return '2.0.0-enterprise';
    }
}

if (!function_exists('tenant_id')) {
    function tenant_id(): ?string
    {
        return request()->header('X-Tenant-ID', request()->route('tenant'));
    }
}
