<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        // Nếu người dùng đã đăng nhập, chuyển hướng về trang chính
        if (Auth::check()) {
            return redirect()->route('admin.index');
        }

        return view('admin.auth.login');
    }

    /**
     * Xử lý yêu cầu đăng nhập
     */
    public function login(Request $request)
    {
        // Nếu người dùng đã đăng nhập, chuyển hướng về trang chính
        if (Auth::check()) {
            return redirect()->route('admin.index');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Lấy URL đã lưu trong session (nếu có)
            $intendedUrl = session('admin_intended_url');

            // Xóa URL đã lưu trong session
            session()->forget('admin_intended_url');

            // Chuyển hướng đến URL đã lưu, nếu không có thì chuyển về trang chính
            if ($intendedUrl) {
                return redirect($intendedUrl);
            }

            return redirect()->intended('admin/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->except('password'));
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
