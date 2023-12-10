<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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

    // ...


    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }
        
        // Validate the update request
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'string|email|unique:users,email,' . $user->id,
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Check and handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image
            $resultDelete = Storage::delete($user->image);

            // Upload new image
            $params['image'] = $resultDelete ? uploadFile('image', $request->file('image')) : $user->image;
        } else {
            // No new image provided
            $params['image'] = $user->image;
        }

        // Update user information based on request data
        $user->update($request->except('_token', 'old_password'));

        // Update password if both old and new passwords are provided
        if ($request->filled('old_password') && $request->filled('new_password') && $request->new_password != null) {
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return response(['msg' => 'Mật khẩu không chính xác'], 401);
            } else {
                $user->forceFill([
                    'password' => bcrypt($request->input('new_password')),
                ])->save();
                return response(['msg' => 'Mật khẩu đã được thay đổi'], 200);
            }
        }
        // Return the updated user as a resource
        return new UserResource($user);
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
