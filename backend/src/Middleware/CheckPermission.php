<?php

namespace Infini\Attendance\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        return $next($request);
    }
}
