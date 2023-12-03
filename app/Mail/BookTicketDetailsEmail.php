<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Book_ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;


class BookTicketDetailsEmail extends Mailable
{
    protected $latestTicket;
    public function __construct($latestTicket)
    {
        $this->latestTicket = $latestTicket;
    }
    use Queueable, SerializesModels;
    public function build()
    {
        $currentUser = Auth::user();
        $arr = [];
        $latestTicket = Book_ticket::where('user_id', $currentUser->id)
            ->latest('created_at')
            ->first();
        $food_ticket_detail = DB::table('food_ticket_details as ftk')
            ->join('book_tickets as btk', 'ftk.book_ticket_id', '=', 'btk.id')
            ->join('food as f', 'ftk.food_id', '=', 'f.id')
            ->select(
                'f.name',
                'ftk.quantity',
                'f.price'
            )->where('btk.id_code', $currentUser->id)
            ->get();
        $arr = [];
        $food_ticket_detail = $food_ticket_detail ? $food_ticket_detail : [];

        foreach ($food_ticket_detail as $value) {

            $arr[] = $value;
        }
        $book_ticket_detail = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->join('films as fl', 'fl.id', '=', 'td.film_id')
            ->join('times as tm', 'tm.id', '=', 'td.time_id')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->select(
                'bt.time',
                'fl.name',
                'bt.id_code',
                'mv.name as movie_room_name',
                'fl.image',
                'cms.name as name_cinema',
                'cms.address',
                'td.date',
                'tm.time as time_suatchieu',
                'bt.amount as total_price',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email'
            )
            ->where('bt.id_code', '=', $latestTicket->id_code)
            ->get()->first();
        if (!$latestTicket) {
            return $this->subject('Thông tin đặt vé xem film - mã thanh toán: Chưa có vé')
                ->markdown('emails.book_ticket_details', ['bookTicketDetails' => null]);
        }
        $bladebarcode = view('emails.file', [
            'bookTicketDetails' => [$book_ticket_detail],
        ])->render();
        $tempFilePath = tempnam(sys_get_temp_dir(), 'email_template_');
        file_put_contents($tempFilePath, $bladebarcode);


        return $this->subject('Thông tin đặt vé xem film - mã thanh toán: ...' . substr($latestTicket->id_code, -7))
            ->markdown('emails.book_ticket_details', ['bookTicketDetails' => [$book_ticket_detail], 'food_ticket_detail' => $arr])
            ->attach($tempFilePath, [
                'as' => 'email_template.html',
                'mime' => 'text/html',
            ]);;
    }
}
