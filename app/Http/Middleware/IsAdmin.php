<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * Kiểm tra user có phải là admin không
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Middleware 'auth' đã chạy trước, nên chúng ta chắc chắn có Auth::user()
        if (Auth::check() && Auth::user()->role !== 'admin') {
            // Nếu không phải admin, redirect về trang chủ user
            return redirect(env('APP_URL', 'http://localhost'));
        }
 
        return $next($request);
    }
}
