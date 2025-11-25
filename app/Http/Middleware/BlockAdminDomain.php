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
        $adminHost = parse_url(config('app.admin_url', 'http://admin.localhost'), PHP_URL_HOST);

        // Nếu đang ở admin domain, chặn user thường
        if ($requestHost === $adminHost) {
            // Lấy URL đầy đủ từ biến môi trường
            $appUrl = rtrim(config('app.url', 'http://localhost'), '/');
            // Redirect về user domain
            return redirect($appUrl . $request->getPathInfo());
        }

        return $next($request);
    }
}

