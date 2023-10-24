<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class QuerryController extends Controller
{

<<<<<<< HEAD
    public function movies_rooms($id)
=======
    public function film_cinema($id)
    {
        $films = DB::table('films')
            ->select('films.*')
            ->join('cinema_details', 'films.id', '=', 'cinema_details.film_id')
            ->join('time_details', 'films.id', '=', 'time_details.film_id')
            ->join('cinemas', 'cinema_details.cinema_id', '=', 'cinemas.id')
            ->where('cinemas.id', $id)
            ->distinct()
            ->get();
        return $films;
    }
    public function categorie_detail_name($id)
    {
        $names = DB::table('categories')
            ->selectRaw('GROUP_CONCAT(categories.name ORDER BY categories.name SEPARATOR ",") as names')

            ->join('category_details', 'categories.id', '=', 'category_details.category_id')

            ->join('films', 'category_details.film_id', '=', 'films.id')

            ->where('films.id', $id)

            ->get()
            ->first()
            ->names;
        return $names;
    }
    public function movie_rooms($id_cinema, $date, $filmId)
>>>>>>> 7bef63ef3b68826728f01362f36bc4e0909f94a9
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
<<<<<<< HEAD
            ])
            ->join('movie_rooms', 'movie_rooms.id', '=', 'time_details.room_id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('times', 'time_details.time_id', '=', 'times.id')
            ->where('time_details.film_id', $id)->get();
=======

            ])

            ->join('movie_rooms', 'movie_rooms.id', '=', 'time_details.room_id')

            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')

            ->join('times', 'time_details.time_id', '=', 'times.id')

            ->where('cinemas.id', $id_cinema)

            ->whereDate('time_details.date', $date)
            ->where('film_id', $filmId)
            ->get();

>>>>>>> 7bef63ef3b68826728f01362f36bc4e0909f94a9
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
<<<<<<< HEAD
}
=======
}
>>>>>>> 7bef63ef3b68826728f01362f36bc4e0909f94a9
