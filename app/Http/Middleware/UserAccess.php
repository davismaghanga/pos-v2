<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserAccess
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
            if (Auth::user()->access != 0){
                return redirect('error/unauthorized')->with('returnUrl', 'admin/home');
            }
        }

        return $response;
    }
}
