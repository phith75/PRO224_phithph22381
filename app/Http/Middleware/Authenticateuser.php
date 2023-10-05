<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Closure;

class AuthenticateUser
{
    public function handle($request, Closure $next)
    {
        // Kiểm tra xác thực người dùng
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
