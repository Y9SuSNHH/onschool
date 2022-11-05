<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    use ResponseTrait;
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== UserRoleEnum::ADMIN) {
            return redirect()->route('users.login');
        }
        return $next($request);
    }
}
