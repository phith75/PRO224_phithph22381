<?php

namespace App\Http\Controllers;

use App\Mail\SampleEmail;
use App\Models\Book_ticket;
use Illuminate\Http\Request;
use App\Mail\BookTicketDetailsEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    // public function sendBookTicketDetailsEmail()
    // {
    //     //lấy thông tin user
    //     $email = Auth::user()->email;
    //     // lấy dữ liệu bảng hiện tại (đang lỗi chưa thấy được theo đúng id user)
    //     Mail::to($email)->send(new BookTicketDetailsEmail());
    //     //thông báo gửi thành công
    //     return response()->json(['message' => 'Email đã được gửi thành công']);
    // }

    // test
    // public function sendEmail(Request $request)
    // {
    //     $user = $request->user();

    //     Mail::to($user->email)->send(new SampleEmail());

    //     return response()->json(['message' => 'Email đã được gửi thành công']);
    // }

    public function sendBookTicketDetailsEmail()
    {
        // Lấy thông tin người dùng hiện tại đã đăng nhập
        $currentUser = Auth::user();

        // Kiểm tra xem người dùng có đăng nhập hay không
        if (!$currentUser) {
            return response()->json(['message' => 'Người dùng chưa đăng nhập.']);
        }

        // Lấy thông tin vé mới nhất của người dùng hiện tại
        $latestTicket = Book_ticket::where('user_id', $currentUser->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Kiểm tra xem người dùng có đặt vé hay chưa
        if (!$latestTicket) {
            return response()->json(['message' => 'Chưa đặt vé.']);
        }
        $email = $currentUser->email;
        Mail::to($email)->send(new BookTicketDetailsEmail($latestTicket));


        return response()->json(['message' => 'Email đã được gửi thành công']);
    }
}
