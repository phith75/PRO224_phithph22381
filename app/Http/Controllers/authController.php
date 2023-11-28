<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Socialite\Facades\Socialite;

class authController extends Controller{
    use HasApiTokens;
    public function sign_up(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ], [
            'name.required' => 'Nhập name.',
            'email.required' => 'Nhập eamil.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Nhập mật khẩu.'
        ]);
    
        // Tạo User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
    
        // Tạo Member
        $member = Member::create([
            'id_card' => sprintf('%08d', $user->id),
            'card_class' => 1,
            'activation_date' => now(),
            'total_spending' => 0,
            'accumulated_points' => 0,
            'points_used' => 0,
            'usable_points' => 0,
            'id_user' => $user->id,
        ]);
    
        // Gán member_id cho User
        
    
        $token = $user->createToken('apiToken')->plainTextToken;
    
        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response($res, 201);
    }
    

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ], [
            'email.required' => 'Nhập email.',
            'password.required' => 'Nhập password.'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'email hoặc mật khẩu không chính xác'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }


    public function logout(Request $request){
        try {
            $user = auth()->user();
            $user->tokens->each->delete();
            return response()->json([
                'message' => 'Logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }
    //đăng nhập bằng tk gg 
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    try {
        $user = Socialite::driver('google')->user();

        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => bcrypt('randompassword')
            ]);

            Auth::login($newUser);
        }

        $token = $newUser->createToken('apiToken')->plainTextToken;

        return response()->json([
            'user' => $newUser,
            'token' => $token,
        ]);
    } catch (\Exception $e) {
        return response([
            'error' => 'Đã xảy ra lỗi khi đăng nhập bằng Google'
        ], 500);
    }
}

}
