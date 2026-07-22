<?php

namespace Infini\Attendance\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactorAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
