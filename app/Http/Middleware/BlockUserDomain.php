<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockUserDomain
{
    /**
     * Handle an incoming request.
     * Chặn admin không được vào user domain
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestHost = $request->getHost();
        $appHost = parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST);
        $adminHost = parse_url(env('ADMIN_URL', 'http://admin.localhost'), PHP_URL_HOST);

        // Nếu đang ở user domain và user là admin, chặn
        if ($requestHost === $appHost) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                // Lấy URL đầy đủ từ biến môi trường
                $adminUrl = rtrim(env('ADMIN_URL', 'http://admin.localhost'), '/');
                // Redirect về admin domain
                return redirect($adminUrl . $request->getPathInfo());
            }
        }

        return $next($request);
    }
}

