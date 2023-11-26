<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::random(8);
            $user->reset_password_token = $token;
            $user->reset_password_token_expiry = now()->addHour(); // Thời gian hết hạn token
            $user->save();

            // Gửi email chứa token tới người dùng
            Mail::to($user->email)->send(new ResetPasswordMail($token));

            return response()->json(['message' => 'Token đặt lại mật khẩu đã được gửi đến email của bạn!']);
        }

        return response()->json(['message' => 'Không tìm thấy người dùng với địa chỉ email này.'], 404);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('reset_password_token', $request->token)
            ->where('reset_password_token_expiry', '>=', now())
            ->first();

        if ($user) {
            $user->forceFill([
                'password' => bcrypt($request->password),
                'remember_token' => Str::random(8),
                'reset_password_token' => null,
                'reset_password_token_expiry' => null,
            ])->save();

            return response()->json(['message' => 'Mật khẩu của bạn đã được đặt lại!']);
        }

        return response()->json(['message' => 'Đặt lại mật khẩu thất bại. Token không hợp lệ hoặc đã hết hạn.'], 401);
    }
}
