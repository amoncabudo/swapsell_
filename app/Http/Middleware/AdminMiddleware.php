<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $role =  \Illuminate\Support\Facades\Auth::user()->role;

        if ($role != 0) {
            return to_route("Welcome"); //Cambiar per pàgina error
        } else {

            return $next($request);
        }
    }
}