<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class authController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công
            return redirect()->intended('/dashboard');
        }

        // Đăng nhập không thành công
        return redirect()->route('login')->with('error', 'Email hoặc mật khẩu không chính xác.');
    }
}
