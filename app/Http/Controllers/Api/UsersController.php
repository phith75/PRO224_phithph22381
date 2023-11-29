<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = User::all();
        return UserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $User = User::create($request->all());
        return new UserResource($User);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $User = User::find($id);
        if (!$User) {
            return response()->json(['message' => "User not found"], 404);
        }
        return $User;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $User = User::find($id);
        if (!$User) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (isset($request->old_password) && !Hash::check($request->input('old_password'), $User->password)) {
            return response([
                'msg' => 'Mật khẩu không chính xác',
            ], 401);
        }
        if (isset($request->old_password) && isset($request->new_password)) {
            $User->forceFill([
                'password' => bcrypt($request->new_password),
            ])->save();
        }
        return new UserResource($User);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $User = User::find($id);
        if (!$User) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $User->delete();
        return response()->json(['message' => "delete success"], 200);
    }
}
