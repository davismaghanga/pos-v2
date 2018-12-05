<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response =  $next($request);
        
        if (Auth::check()) {
            if (Auth::user()->access != 1){
                return redirect('error/unauthorized')->with('returnUrl', 'home');
            }
        }

        return $response;
    }
}
