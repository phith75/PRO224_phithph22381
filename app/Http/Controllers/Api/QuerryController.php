<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class QuerryController extends Controller
{

    public function movies_rooms($id)
    {
        $movieRooms = DB::table('time_details')
            ->select([
                'movie_rooms.id AS movie_rooms',
                'cinemas.id AS id_cinema',
                'cinemas.name AS cinema_name',
                'time_details.id AS time_detail_id',
                DB::raw("CONCAT(DATE_FORMAT(time_details.date, '%d/%m'), ' - ', DATE_FORMAT(time_details.date, '%W')) AS formatted_date"),
                'times.time',
                'film_id',
            ])
            ->join('movie_rooms', 'movie_rooms.id', '=', 'time_details.room_id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('times', 'time_details.time_id', '=', 'times.id')
            ->where('time_details.film_id', $id)->get();
        return $movieRooms;
    }
    public function chair_status($id)
    {
        $chairs = DB::table('movie_chairs as mc')
            ->select('mc.name')
            ->where('mc.id_time_detail', $id)
            ->get();
        $chair_array = $chairs->pluck('name')->toArray();
        return $chair_array;
    }
    public function chair_count($id)
    {
        $count = DB::table('book_ticket_details as btd')
            ->selectRaw('COUNT(distinct btd.chair) as total_chairs')
            ->join('time_details as td', 'td.id', '=', 'btd.time_id')
            ->where('td.id', $id)
            ->first();
        return $count;
    }
}
