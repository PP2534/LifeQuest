<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminDomain
{
    /**
     * Handle an incoming request.
     * Chặn user thường không được vào admin domain
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestHost = $request->getHost();
        $adminHost = parse_url(env('ADMIN_URL', 'http://admin.localhost'), PHP_URL_HOST);

        // Nếu đang ở admin domain, chặn user thường
        if ($requestHost === $adminHost) {
            // Lấy URL đầy đủ từ biến môi trường
            $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
            // Redirect về user domain
            return redirect($appUrl . $request->getPathInfo());
        }

        return $next($request);
    }
}

