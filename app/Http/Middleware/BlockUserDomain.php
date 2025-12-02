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
        $appHost = parse_url(config('app.url', 'http://localhost'), PHP_URL_HOST);
        $adminHost = parse_url(config('app.admin_url', 'http://admin.localhost'), PHP_URL_HOST);

        // Nếu đang ở user domain và user là admin, chặn
        if ($requestHost === $appHost) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                // Lấy URL đầy đủ từ biến môi trường
                $adminUrl = rtrim(config('app.admin_url', 'http://admin.localhost'), '/');
                // Redirect về admin domain
                return redirect($adminUrl . $request->getPathInfo());
            }
        }

        return $next($request);
    }
}

