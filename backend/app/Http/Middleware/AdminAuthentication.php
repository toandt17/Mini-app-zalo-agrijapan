<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu người dùng chưa đăng nhập
        if (!Auth::check()) {
            // Lưu URL hiện tại vào session để redirect sau khi đăng nhập
            session(['admin_intended_url' => $request->fullUrl()]);

            // Chuyển hướng về trang đăng nhập admin
            return redirect()->route('admin.login');
        }

        // Nếu đã đăng nhập, cho phép tiếp tục
        return $next($request);
    }
}
