<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDomain
{
    /**
     * Handle an incoming request.
     * Kiểm tra domain và quyền truy cập
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestHost = $request->getHost();
        $requestPort = $request->getPort();
        $requestUrl = $request->getPathInfo();
        $adminHost = parse_url(env('ADMIN_URL', 'http://admin.localhost'), PHP_URL_HOST) ?: 'admin.localhost';
        $appHost = parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost';
        
        // Lấy port từ request hoặc từ env
        $port = $requestPort ?: parse_url(env('APP_URL', 'http://localhost:8000'), PHP_URL_PORT) ?: 8000;
        $adminUrl = ($request->getScheme() ?? 'http') . '://' . $adminHost . ':' . $port;
        $appUrl = ($request->getScheme() ?? 'http') . '://' . $appHost . ':' . $port;

        // Nếu đang ở admin domain
        if ($requestHost === $adminHost) {
            if (str_contains($requestUrl, '/login')) {
                // Nếu đã đăng nhập và là admin, redirect về dashboard để tránh lặp
                if (Auth::check() && Auth::user() && Auth::user()->role === 'admin') {
                    return redirect($adminUrl . '/dashboard');
                }
                return $next($request);
            }
            if (Auth::check() && Auth::user() && Auth::user()->role !== 'admin') {
                return redirect($adminUrl . '/login');
            }
        }

        // Nếu đang ở user domain
        if ($requestHost === $appHost) {
            // Nếu user là admin và không phải trang login, redirect về admin
            if (Auth::check() && Auth::user()->role === 'admin' && !str_contains($requestUrl, '/login')) {
                return redirect($adminUrl . '/dashboard');
            }
        }

        // Nếu domain không khớp với cả admin và user domain, chặn
        if ($requestHost !== $adminHost && $requestHost !== $appHost) {
            // Redirect về user domain
            return redirect($appUrl . $requestUrl);
        }
 
        return $next($request);
    }
}
