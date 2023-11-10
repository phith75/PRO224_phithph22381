<?php

namespace App\Http\Controllers;

use App\Mail\SampleEmail;
use App\Models\Book_ticket;
use Illuminate\Http\Request;
use App\Mail\BookTicketDetailsEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


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
        $currentUser = Auth::user();
        $idUsers = Book_ticket::pluck('user_id');
        $matchingIdUsers = $idUsers->filter(function ($id) use ($currentUser) {
            return $id == $currentUser->id;
        });
        if ($matchingIdUsers->isEmpty()) {
            return response()->json(['message' => 'Chưa đặt vé.']);
        }
        $emails = User::whereIn('id', $matchingIdUsers)->pluck('email')->toArray();
        foreach ($emails as $email) {
            Mail::to($email)->send(new BookTicketDetailsEmail());
        }
        return response()->json(['message' => 'Email đã được gửi thành công']);
    }
}
