<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XssMiddleware
{
    public function handle($request, Closure $next)
    {
        $userInput = $request->all();
        array_walk_recursive($userInput, static function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);
        return $next($request);
    }
}
