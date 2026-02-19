<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Added this import
use Symfony\Component\HttpFoundation\Response;

class ApprovedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Using Auth facade fixes the Intelephense errors
        if (Auth::check() && Auth::user()->status !== 'approved') {
            Auth::logout(); // <-- Fixed here
            
            return redirect()->route('login')->with('error', 'Your account is pending admin approval or has been rejected.');
        }
        
        return $next($request);
    }
}