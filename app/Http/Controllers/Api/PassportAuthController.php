<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PassportAuthController extends Controller
{
    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('Laravel10PassportAuth')->accessToken;

        return response()->json(['token' => $token, 'success'], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = auth()->user()->createToken('Laravel8PassportAuth')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function userInfo()
    {

        $user = auth()->user();

        return response()->json(['user' => $user], 200);
    }
}
