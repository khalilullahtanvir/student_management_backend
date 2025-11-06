<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureFrontendRequestsAreStateful
{
    public function handle(Request $request, Closure $next)
    {
        // এখানে আপনার লজিক যোগ করুন
        return $next($request);
    }
}
