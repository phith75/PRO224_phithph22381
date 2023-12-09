<?php

namespace App\Http\Controllers;

use App\Models\Food_ticket_detail;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function printTicket($id)
    {
        $ticket = DB::table('book_tickets')->where('id_code', $id)->update(["status" => 2]);
        // Logic để lấy thông tin vé từ $ticketId
        $book_ticket_detail = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->join('films as fl', 'fl.id', '=', 'td.film_id')
            ->join('times as tm', 'tm.id', '=', 'td.time_id')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->leftJoin('food_ticket_details as ftd', 'ftd.book_ticket_id', '=', 'bt.id')
            ->leftJoin('food', 'food.id', '=', 'ftd.food_id')
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
                DB::raw('IFNULL(food.name, NULL) as food_name'),
                DB::raw('IFNULL(food.price, NULL) as food_price'),
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email'
            )
            ->where('bt.id_code', '=', $id)
            ->get()
            ->first();
        // Kiểm tra xem có thông tin food ticket hay không
        $food_ticket_exists = !is_null($book_ticket_detail->food_name) && !is_null($book_ticket_detail->food_price);

        // Nếu có thông tin food ticket, thực hiện xử lý
        if ($food_ticket_exists) {
            // Có thể thực hiện các thao tác khác dựa trên thông tin food ticket
        }
        $array_chair = explode(",", $book_ticket_detail->chair_name);

        // $ticketData = [
        //     'ticket_id' => $ticketId,
        //     'name' => 'John Doe', // Thông tin khác về vé
        //     // ...
        // ];
        $arr = [];
        $food_ticket_detail = DB::table('food_ticket_details as ftk')
            ->join('book_tickets as btk', 'ftk.book_ticket_id', '=', 'btk.id')
            ->join('food as f', 'ftk.food_id', '=', 'f.id')
            ->select(
                'f.name',
                'ftk.quantity',
                'f.price'
            )->where('btk.id_code', $id)
            ->get();

        foreach ($food_ticket_detail as $value) {

            $arr[] = $value;
        }
        $food_ticket_detail = $arr;
        $pdf = PDF::loadView('tickets.print', compact('book_ticket_detail', 'array_chair', 'food_ticket_detail'));

        // Gửi PDF cho trình duyệt để hiển thị hoặc tải xuống
        return $pdf->stream('ticket.pdf');
    }
}
