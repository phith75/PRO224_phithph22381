<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class authController extends Controller{
    use HasApiTokens;
    public function sign_up(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ], [
            'name.required' => 'Nhập name.',
            'email.required' => 'Nhập eamil.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Nhập mất khẩu.'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

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
}
