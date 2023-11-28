<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class authController extends Controller
{
    use HasApiTokens;

    public function sign_up(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'phone' => 'nullable|string',         
            'image' => 'nullable|string',         
            'date_of_birth' => 'nullable|date',
        ], [
            'name.required' => 'Nhập name.',
            'email.required' => 'Nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Nhập mật khẩu.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'phone' => $request->input('phone'),
            'image' => $request->input('image'),
            'date_of_birth' => $request->input('date_of_birth'),
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
            'token' => $token,
        ];

        return response($res, 201);
    }
    

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Nhập email.',
            'password.required' => 'Nhập mật khẩu.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response([
                'msg' => 'Email hoặc mật khẩu không chính xác',
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token,
        ];

        return response($res, 201);
    }

    // Rest of your code...
}
