<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class QuerryController extends Controller
{

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

            ->where('cinemas.id', $id_cinema)

            ->whereDate('time_details.date', $date)
            ->where('film_id', $filmId)
            ->get();

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
        $result = DB::table('movie_chairs')
            ->selectRaw('GROUP_CONCAT(name) as number')
            ->where('id_time_detail', $id)
            ->get();
        $num = '';
        foreach ($result as $row) {
            $num =  $row->number;
        }
        return $check_lenght = 70 - (strlen($num) - strlen(str_replace(",", "", $num)) + 1);
    }

    public function cache_seat(Request $request)
    {
        $currentTime = Carbon::now();
        $seat_reservation = Cache::get('seat_reservation', []);

        // Kiểm tra xem đã có thông tin cho id_user và id_time_detail chưa
        if (!isset($seat_reservation[$request->id_time_detail][$request->id_user])) {
            $seat_reservation[$request->id_time_detail][$request->id_user] = [
                'seat' => [],
                'time' => [],
            ];
        }

        // Kiểm tra xem ghế đã được đặt chưa
        if (!in_array($request->selected_seats, $seat_reservation[$request->id_time_detail][$request->id_user]['seat'])) {
            $seat_reservation[$request->id_time_detail][$request->id_user]['seat'][] = $request->selected_seats;
            $seat_reservation[$request->id_time_detail][$request->id_user]['time'][$request->selected_seats] = $currentTime->addMinutes(1);
        }
        // Đặt lại dữ liệu vào Cache
        Cache::put('seat_reservation', $seat_reservation, $currentTime->addMinutes(1));

        // Trả về dữ liệu ghế và thời gian đã đặt
        return $seat_reservation[$request->id_time_detail][$request->id_user];
    }
    public function getReservedSeatsByTimeDetail($id_time_detail)
    {
        $seat_reservation = Cache::get('seat_reservation', []);
        $reservedSeats = [];

        if (isset($seat_reservation[$id_time_detail])) {
            foreach ($seat_reservation[$id_time_detail] as $userData) {
                // Lấy danh sách ghế được giữ cho mỗi người dùng
                $userSeats = $userData['seat'];
                // Thêm ghế vào danh sách ghế đã được giữ
                $reservedSeats = array_merge($reservedSeats, $userSeats);
            }
        }

        // Lọc và loại bỏ các giá trị trùng lặp (nếu có)
        $reservedSeats = array_unique($reservedSeats);

        return $reservedSeats;
    }
}
