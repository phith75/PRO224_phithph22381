<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    public function categorie_detail_name()
    {
        $names = DB::table('category_details')
            ->select('film_id as id', DB::raw('GROUP_CONCAT(categories.name ORDER BY categories.name SEPARATOR ",") as category_names'))
            ->join('categories', 'category_details.category_id', '=', 'categories.id')
            ->groupBy('film_id')  // Thêm mệnh đề GROUP BY
            ->get();
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
    public function chair_count()
    {
        $sql = DB::table('time_details')
            ->select('id')->get();
        $arr_list_chair_count = [];
        $arr_id = [];
        foreach ($sql as $row) {
            $arr_id[] =  $row->id;
        }
        foreach ($arr_id as $key => $value) {
            $result = DB::table('movie_chairs')
                ->selectRaw('GROUP_CONCAT(name) as number')
                ->where('id_time_detail', $value)
                ->get();
            $num = '';
            foreach ($result as $row) {
                $num =  $row->number;
            }
            $check_lenght = 70 - (strlen($num) - strlen(str_replace(",", "", $num)) + 1);

            if ($num == null) {
                $check_lenght = 70;
            }
            $arr_list_chair_count['id'] = $value;

            $arr_list_chair_count['empty_chair'] = $check_lenght;
        }
        return $arr_list_chair_count;
    }

    public function cache_seat(Request $request)
    {
        $currentTime = Carbon::now();
        $seat_reservation = Cache::get('seat_reservation', []);
        preg_match('/([A-Za-z]+)([0-9]+)/', $request->selected_seats, $matches);
        $string_seat = $matches[1];
        $number_seat = intval($matches[2]);
        // Kiểm tra xem đã có thông tin cho id_user và id_time_detail chưa
        if (!isset($seat_reservation[$request->id_time_detail][$request->id_user])) {
            $seat_reservation[$request->id_time_detail][$request->id_user] = [
                'seat' => [],
                'time' => [],
                'price' => [],

            ];
        }
        // Kiểm tra xem ghế đã được đặt chưa
        $selected_seats = explode(',', $request->selected_seats);

        // Kiểm tra xem có sự trùng lặp về id_user và số ghế không
        if (
            in_array($request->id_user, array_keys($seat_reservation[$request->id_time_detail])) &&
            count(array_intersect($selected_seats, $seat_reservation[$request->id_time_detail][$request->id_user]['seat'])) > 0
        ) {
            // Hủy giữ ghế
            foreach ($selected_seats as $seat) {
                $index = array_search($seat, $seat_reservation[$request->id_time_detail][$request->id_user]['seat']);
                if (($number_seat == 1 && in_array($string_seat . "2", $seat_reservation[$request->id_time_detail][$request->id_user]['seat'])) || ($number_seat == 12 && in_array($string_seat . "11", $seat_reservation[$request->id_time_detail][$request->id_user]['seat']))) {
                    if ($number_seat == 1) {
                        $checked_seat = "2";
                        $key = array_search($string_seat . $checked_seat, $seat_reservation[$request->id_time_detail][$request->id_user]['seat']);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['seat'][$key]);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['time'][$string_seat . $checked_seat]);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['price'][$string_seat . $checked_seat]);
                    } else {

                        $checked_seat = "11";
                        $key = array_search($string_seat . $checked_seat, $seat_reservation[$request->id_time_detail][$request->id_user]['seat']);

                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['seat'][$key]);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['time'][$string_seat . $checked_seat]);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['price'][$string_seat . $checked_seat]);
                    }
                    if ($index !== false) {
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['seat'][$index]);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat]);
                        unset($seat_reservation[$request->id_time_detail][$request->id_user]['price'][$seat]);
                    }
                }
            }
        } elseif (count(array_intersect($selected_seats, Arr::flatten($seat_reservation[$request->id_time_detail]))) === 0) {
            // Đặt ghế mới chỉ nếu ghế chưa được đặt bởi bất kỳ ai khác
            $checked_seat = 0;

            if (($number_seat == 2 && !in_array($string_seat . "1", $seat_reservation[$request->id_time_detail][$request->id_user]['seat'])) || ($number_seat == 11 && !in_array($string_seat . "12", $seat_reservation[$request->id_time_detail][$request->id_user]['seat']))) {
                if ($number_seat == 2) {
                    $checked_seat = "1";
                } else {
                    $checked_seat = "12";
                }
                $message = "khong duoc bo trong ghe " . $string_seat . $checked_seat;
                return json_encode(['alert' => $message]);
            }


            foreach ($selected_seats as $seat) {
                $seat_reservation[$request->id_time_detail][$request->id_user]['seat'][] = $seat;
                $seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat] = $currentTime->addMinutes(2);
                $seat_reservation[$request->id_time_detail][$request->id_user]['price'][$seat] = intval($request->price);
            }
        } else {
            // Trả về thông báo rằng ghế đã được đặt
            return response()->json(['message' => 'Ghế đã được đặt bởi người dùng khác.'], 403);
        }

        // Đặt lại dữ liệu vào Cache
        Cache::put('seat_reservation', $seat_reservation, $currentTime->addMinutes(2));

        // Trả về dữ liệu ghế và thời gian đã đặt

        return $seat_reservation[$request->id_time_detail];
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
    public function purchase_history_ad()
    {
        $detail_purchase = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('food_ticket_details as ftd', 'ftd.book_ticket_id', '=', 'bt.id')
            ->join('food', 'food.id', '=', 'ftd.food_id')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->select(
                'bt.time',
                'bt.amount as total_price',
                'food.name as food_name',
                'food.image as food_image',
                'food.price as food_price',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.image as users_image',
                'users.email as users_email'
            )
            ->get();
        return $detail_purchase;
    }
    public function purchase_history_user($id)
    {
        $detail_purchase = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('food_ticket_details as ftd', 'ftd.book_ticket_id', '=', 'bt.id')
            ->join('food', 'food.id', '=', 'ftd.food_id')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->select(
                'bt.time',
                'bt.amount as total_price',
                'food.name as food_name',
                'food.image as food_image',
                'food.price as food_price',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.image as users_image',
                'users.email as users_email'
            )
            ->where('users.id', $id)
            ->get();
        return $detail_purchase;
    }
    public function QR_book_tiket($id)
    {
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
            ->where('id_code', '=', $id)
            ->get()->first();
        return view('book_ticket_QR', ['bookTicketDetails' => [$book_ticket_detail]]);
    }
    public function Revenue(Request $request)
    {
        //thống kê từ lúc bắt đầu đến hiện tại và lọc theo tháng
        $n = date("Y");
        $year = '';
        if ($request->date != $n) {
            $year = $request->date;
        } else {
            $year =  date("Y");
        }

        $revenue_month_y = DB::table('book_tickets')
            ->select(DB::raw('DATE_FORMAT(created_at, "%m") as Month'), DB::raw('SUM(amount) as TotalAmount'))
            ->whereYear('created_at', $year)
            ->groupBy('Month')
            ->get();
        //-------------------------------------------


        //lấy theo tháng
        $years = date('Y');
        $month = date('m');
        $revenue_mon = DB::table('book_tickets')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as Month'), DB::raw('SUM(amount) as TotalAmount'))
            ->whereYear('created_at', $years)
            ->whereMonth('created_at', $month)
            ->groupBy('Month')
            ->get();
        //----------------------------------------------------
        //thống kê tổng số khách hàng mới của của tháng này


        $now = Carbon::now();
        $newUsers = User::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        //-------------------------------------
        //lấy ra film có doanh thu cao nhất tháng

        $revenue_film = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->whereYear('book_tickets.created_at', $now->year)
            ->whereMonth('book_tickets.created_at', $now->month)
            ->groupBy('films.name')
            ->orderBy('TotalAmount', 'desc')
            ->take(5)
            ->get();

        //----------------------------------------------------------------
        //lấy ra 5 khách hàng thân thiết
        $user_friendly = DB::table('book_tickets')
            ->join('users', 'book_tickets.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->groupBy('users.name')
            ->orderBy('TotalAmount', 'desc')
            ->take(5)
            ->get();

        //----------------------------------------------------------------
        //lấy ra tổng số vé bán ra trong tháng theo từng phim
        $total_book = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as TotalTickets'))
            ->whereYear('time_details.date', $now->year)
            ->whereMonth('time_details.date', $now->month)
            ->groupBy('films.name')
            ->get();
        //----------------------------------------------------------------
        // lấy ra so sánh doanh thu tháng này với tháng trước

        $month2 = $now->month;
        $year2 = $now->year;

        // Lấy tháng và năm trước
        $lastMonth = $now->copy()->subMonth();
        $lastMonthNumber = $lastMonth->month;
        $lastYear = $lastMonth->year;

        // Tính toán doanh thu tháng hiện tại
        $currentMonthRevenue = DB::table('book_tickets')
            ->whereMonth('time', $month2)
            ->whereYear('time', $year2)
            ->sum('amount');

        // Tính toán doanh thu tháng trước
        $lastMonthRevenue = DB::table('book_tickets')
            ->whereMonth('time', $lastMonthNumber)
            ->whereYear('time', $lastYear)
            ->sum('amount');

        // So sánh doanh thu
        $comparison = $currentMonthRevenue - $lastMonthRevenue;
        $revenueToday = DB::table('book_tickets')

            ->whereDate('time', $now)
            ->sum('amount');


        //-------------------------------
        //lấy ra khách hàng mới trong ngày
        $newUsers = User::whereYear('created_at', $now->year)
            ->whereDate('created_at', $now)
            ->count();
        //------------------------------------
        //lấy ra film có doanh thu cao nhất trong ngày

        $revenue_film = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->whereDate('book_tickets.time', $now)
            ->groupBy('films.name')
            ->orderBy('TotalAmount', 'desc')
            ->take(5)
            ->get();

        //----------------------------------------------------------------
        //lấy ra tổng số vé bán ra trong ngày theo từng phim 

        $book_total = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as TotalTickets'))
            ->whereDate('time_details.date', $now)
            ->groupBy('films.name')
            ->get();




        $data = [
            "revenue_day" => [
                "revenueToday" => $revenueToday,
                'revenue_film_day' => $revenue_film,
                'newUsers' => $newUsers,
                'book_total' => $book_total,
            ],
            "revenue_month" => [
                'revenue_month_y' => $revenue_month_y,
                'revenue_mon' => $revenue_mon,
                'newUsers' => $newUsers,
                'revenue_film' => $revenue_film,
                'user_friendly' => $user_friendly,
                'total_book' => $total_book,
                'comparison' => $comparison
            ]


        ];


        return $data;
    }
    public function time_detail_get_by_id($id)
    {
        $CinemaDetailbyId = DB::table('cinemas')
            ->join('movie_rooms', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->join('time_details', 'movie_rooms.id', '=', 'time_details.room_id')
            ->join('films', 'films.id', '=', 'time_details.film_id')
            ->join('times', 'times.id', '=', 'time_details.time_id')
            ->select(
                'cinemas.id as id_cinema',
                'cinemas.address as adrress_cinema',
                'cinemas.name as name_cinema',
                'cinemas.status as status_cinema',
                'time_details.film_id',
                'films.name as name_film',
                'films.image as image_film',
                'films.trailer as id_trailer',
                'films.release_date',
                'films.end_date',
                'films.description',
                'films.status as status_film',
                'time_details.time_id',
                'times.time',
                'time_details.room_id',
                'time_details.date',
                'movie_rooms.name as room_name',
            )->where('time_details.id', $id)
            ->first();
        return $CinemaDetailbyId;
    }
}
