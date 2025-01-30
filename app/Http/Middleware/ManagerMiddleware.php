<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $role =  \Illuminate\Support\Facades\Auth::user()->role;

        if ($role > 1) {
            return to_route("Welcome"); //Cambiar per pàgina error
        } else {

            return $next($request);
        }
    }
}