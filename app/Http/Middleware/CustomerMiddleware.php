<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if(auth()->check() && auth()->user()->is_admin == 0){
        if(auth('customer')->check()){
            return $next($request);
        }
        // return redirect()->route('login')->with('error', 'You are not authorized to access this page.');
        return redirect()->route('login');
    }
}
