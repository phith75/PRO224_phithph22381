<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\member;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Models\social_networks;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            'coin' => 'nullable',

        ], [
            'name.required' => 'Nhập name.',
            'email.required' => 'Nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Nhập mật khẩu.',
        ]);



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


    public function logout(Request $request)
    {
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

    public function facebook(Request $request)
    {
        $facebook = $request->only('access_token');
        if (!$facebook || !isset($facebook['access_token'])) {
            return $this->responseErrors(config('code.user.login_facebook_failed'), trans('messages.user.login_facebook_failed'));
        }
        // Khởi tạo instance của Facebook Graph SDK
        $fb = new Facebook([
            'app_id' => config('services.facebook.app_id'),
            'app_secret' => config('services.facebook.app_secret'),
        ]);

        try {
            $response = $fb->get('/me?fields=id,name,email,link,birthday', $facebook['access_token']); // Lấy thông tin 
            // user facebook sử dụng access_token được gửi lên từ client
            $profile = $response->getGraphUser();
            if (!$profile || !isset($profile['id'])) { // Nếu access_token không lấy đc thông tin hợp lệ thì trả về login false luôn
                return $this->responseErrors(config('code.user.login_facebook_failed'), trans('messages.user.login_facebook_failed'));
            }

            $email = $profile['email'] ?? null;
            $social = social_networks::where('social_id', $profile['id'])->where('type', config('user.social_network.type.facebook'))->first();
            // Lấy được userId của Facebook ta kiểm tra trong bảng social_networks đã có chưa, nếu có thì tài khoản facebook này 
            // đã từng đăng nhập vào hệ thống ta chỉ cần lấy ra user rồi generate jwt trả về cho client; Ngược lại nếu chưa có thì 
            // ta sẽ tiếp tục dùng email trả về từ facebook kiểm tra xem nếu có user với email như thế rồi thì lấy luôn user đó nếu 
            // không thì tạo user mới với email trên và tạo bản ghi social_network lưu thông tin userId của facebook rồi generate   
            // để trả về cho client
            if ($social) {
                $user = $social->user;
            } else {
                $user = $email ? User::firstOrCreate(['email' => $email]) : User::create();
                $user->socialNetwork()->create([
                    'social_id' => $profile['id'],
                    'type' => config('user.social_network.type.facebook'),
                ]);
                $user->name = $profile['name'];
                $user->save();
            }

            $token = JWTAuth::fromUser($user);

            return $this->responseSuccess(compact('token', 'user'));
        } catch (\Exception $e) {
            Log::error('Error when login with facebook: ' . $e->getMessage());
            return $this->responseErrors(config('code.user.login_facebook_failed'), trans('messages.user.login_facebook_failed'));
        }
    }

}
