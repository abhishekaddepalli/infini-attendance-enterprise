<?php

namespace Infini\Attendance\Middleware;

use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = $request->header('X-Tenant-ID') ?: $request->route('tenant');
        if ($tenant) {
            config(['infini.current_tenant' => $tenant]);
        }
        return $next($request);
    }
}
