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
        $latestTicket = Book_ticket::where('user_id', $currentUser->id)
            ->latest('created_at')
            ->first();
        $book_ticket_detail = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('food_ticket_details as ftd', 'ftd.book_ticket_id', '=', 'bt.id')
            ->join('food', 'food.id', '=', 'ftd.food_id')
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
                'food.name as food_name',
                'food.price as food_price',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email'
            )
            ->where('bt.id_code', '=', $latestTicket->id_code)
            ->get()->first();

        $book_ticket_detail_arr = [];
        // dd($book_ticket_detail);
        // foreach ($book_ticket_detail as $bt => $value) {
        //     $book_ticket_detail_arr[$bt] = $value;
        // }


        if (!$latestTicket) {
            return $this->subject('Thông tin đặt vé xem film - mã thanh toán: Chưa có vé')
                ->markdown('emails.book_ticket_details', ['bookTicketDetails' => null]);
        }
        return $this->subject('Thông tin đặt vé xem film - mã thanh toán: ' . $latestTicket->id_code)
            ->markdown('emails.book_ticket_details', ['bookTicketDetails' => [$book_ticket_detail]]);
    }
}