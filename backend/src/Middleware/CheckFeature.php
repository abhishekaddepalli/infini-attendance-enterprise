<?php

namespace Infini\Attendance\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        return $next($request);
    }
}
