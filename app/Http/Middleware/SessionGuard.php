<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class SessionGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        if ($guard == 'admin') {
            Config::set('session.cookie', 'admin_session');
        } 
        else if ($guard == 'shop') {
            Config::set('session.cookie', 'shop_session');
        }

        return $next($request);
    }
}
