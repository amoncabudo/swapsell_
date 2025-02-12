<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response //Function to handle the middleware
    {
        $role =  \Illuminate\Support\Facades\Auth::user()->role; //Get the role of the user

        if ($role > 1) {
            return to_route("Welcome"); //Change to the welcome page
        } else {

            return $next($request); //Return the next request
        }
    }
}