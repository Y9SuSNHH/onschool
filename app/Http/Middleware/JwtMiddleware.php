<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class JwtMiddleware
{
    use ResponseTrait;

    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return $this->errorResponse('Unauthorized', 401);
        }
        return $next($request);
    }
}
