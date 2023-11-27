<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function printTicket($id)
    {
        // Logic để lấy thông tin vé từ $ticketId
        $book_ticket_detail = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('food_ticket_details as ftd', 'ftd.book_ticket_id', '=', 'bt.id')
            ->join('food', 'food.id', '=', 'ftd.food_id')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->join('films as fl', 'fl.id', '=', 'td.film_id')
            ->join('times as tm', 'tm.id', '=', 'td.time_id')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->select(
                'bt.created_at as time',
                'fl.name',
                'bt.id_code',
                'mv.name as movie_room_name',
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
            ->where('id_code', '=', $id)
            ->get()->first();
        $array_chair = explode(",", $book_ticket_detail->chair_name);

        // $ticketData = [
        //     'ticket_id' => $ticketId,
        //     'name' => 'John Doe', // Thông tin khác về vé
        //     // ...
        // ];

        $pdf = PDF::loadView('tickets.print', compact('book_ticket_detail', 'array_chair'));

        // Gửi PDF cho trình duyệt để hiển thị hoặc tải xuống
        return $pdf->stream('ticket.pdf');
    }
}