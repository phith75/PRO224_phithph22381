<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food_ticket_detail;
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
    public function chair_by_time_detail($id)
    {


        $chairs = DB::table('movie_chairs as mc')
            ->selectRaw('GROUP_CONCAT(name) as name')
            ->where('mc.id_time_detail', $id)
            ->first(); // Use first() instead of get()
        // Check if $chairs is not null
        if ($chairs) {
            // Split the concatenated string into an array
            $chair_array = explode(',', $chairs->name);
            // If you want to return it as a JSON response
            return $chair_array;
        }
        return [];
        // Handle the case where $chairs is null, e.g., no data found

    }

    public function chair_count()
    {
        $sql = DB::table('time_details')
            ->select('id')->get();
        $arr_list_chair_count = [];

        foreach ($sql as $row) {
            $result = DB::table('movie_chairs')
                ->selectRaw('GROUP_CONCAT(name) as number')
                ->where('id_time_detail', $row->id)
                ->get();

            $num = '';
            foreach ($result as $rowResult) {
                $num = $rowResult->number;
            }

            $check_length = 70 - (strlen($num) - strlen(str_replace(",", "", $num)) + 1);

            if ($num == null) {
                $check_length = 70;
            }

            $arr_list_chair_count[] = [
                'id' => $row->id,
                'empty_chair' => $check_length,
            ];
        }

        return $arr_list_chair_count;
    }


    public function cache_seat(Request $request)
    {
        $id_time_detail = $request->id_time_detail;

        $currentTime = Carbon::now();
        $seat_reservation = Cache::get('seat_reservation', []);
        // Kiểm tra xem đã có thông tin cho id_user và id_time_detail chưa
        $seat_reservation[$id_time_detail][$request->id_user] ??= [
            'seat' => [],
            'time' => [],
        ];
        $selected_seats = explode(',', $request->selected_seats);
        // Kiểm tra ghế đã được đặt
        if (
            in_array($request->id_user, array_keys($seat_reservation[$id_time_detail])) &&
            count(array_intersect($selected_seats, $seat_reservation[$id_time_detail][$request->id_user]['seat'])) > 0
        ) {
            foreach ($selected_seats as $seat) {
                $index = array_search($seat, $seat_reservation[$request->id_time_detail][$request->id_user]['seat']);
                if ($index !== false) {
                    unset($seat_reservation[$request->id_time_detail][$request->id_user]['seat'][$index]);
                    unset($seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat]);
                }
            }
            //   Thêm ghế vào cache
        } elseif (count(array_intersect($selected_seats, Arr::flatten($seat_reservation[$id_time_detail]))) === 0) {
            foreach ($selected_seats as $seat) {
                $seat_reservation[$request->id_time_detail][$request->id_user]['seat'][] = $seat;
                $seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat] = $currentTime->addMinutes(2);
            }
        }
        // Đặt lại dữ liệu vào Cache
        Cache::put('seat_reservation', $seat_reservation, $currentTime->addMinutes(2));

        // Trả về dữ liệu ghế và thời gian đã đặt
        return $seat_reservation[$id_time_detail];
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
            ->leftJoin(DB::raw('(SELECT book_ticket_id, GROUP_CONCAT(name) as food_names FROM food_ticket_details JOIN food ON food.id = food_ticket_details.food_id GROUP BY book_ticket_id) as food_ticket_details'), function ($join) {
                $join->on('food_ticket_details.book_ticket_id', '=', 'bt.id');
            })
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->select(
                'bt.time',
                'bt.amount as total_price',
                'food_ticket_details.food_names',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email'
            )
            ->where('users.id', $id)
            ->get();




        return $detail_purchase;
    }
    public function QR_book_tiket($id)
    {
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
        $book_ticket_detail = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('times', 'times.id', '=', 'td.time_id')
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
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email'
            )
            ->where('id_code', '=', $id)
            ->get()->first();
        return view('book_ticket_QR', ['bookTicketDetails' => [$book_ticket_detail], 'food_ticket_detail' => $arr]);
    }
    public function Revenue(Request $request)
    {
        $now = Carbon::now();
        //thống kê từ lúc bắt đầu đến hiện tại và lọc theo tháng
        $y = '';
        $m  = '';


        if ($request->month === null) {
            $m = date('m');
        } else {
            $m = $request->month;
        };


        if ($request->year === null) {
            $y = date('Y');
        } else {
            $y = $request->year;
        }





        $revenue_month_y = DB::table('book_tickets')
            ->when($m, function ($query, $m) {
                return $query->whereMonth('time', $m);
            }, function ($query) {
                return $query->whereMonth('time', date('m'));
            })
            ->when($y, function ($query, $y) {
                return $query->whereYear('time', $y);
            }, function ($query) {
                return $query->whereYear('time', date('Y'));
            })
            ->sum('amount');

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
        $book_total_mon = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as TotalTickets'))
            ->whereYear('book_tickets.time', $now->year)
            ->whereMonth('book_tickets.time', $now->month)
            ->groupBy('films.name')
            ->get();


        //----------------------------------------------------------------
        //lấy ra tống doanh thu từ đồ ăn theo tháng
        $totalPricefoodmon = DB::table('food_ticket_details')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->whereMonth('food_ticket_details.created_at', '=', $now->month)
            ->whereYear('food_ticket_details.created_at', '=', $now->year)
            ->sum(DB::raw('food.price'));
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

        $book_total_day = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as TotalTickets'))
            ->whereDate('book_tickets.time', $now)
            ->groupBy('films.name')
            ->get();


        $totalPricefoodday = DB::table('food_ticket_details')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->whereDate('food_ticket_details.created_at', '=', $now)
            ->sum(DB::raw('food.price'));



        $data = [
            "revenue_day" => [
                "revenueToday" => $revenueToday,
                'revenue_film_day' => $revenue_film,
                'newUsers' => $newUsers,
                'book_total_day' => $book_total_day,
                'totalPricefoodday' => $totalPricefoodday,
            ],
            "revenue_month" => [
                'revenue_month_y' => $revenue_month_y,
                'revenue_mon' => $revenue_mon,
                'newUsers' => $newUsers,
                'revenue_film' => $revenue_film,
                'user_friendly' => $user_friendly,
                'book_total_mon' => $book_total_mon,
                'comparison' => $comparison,
                'totalPricefoodmon' => $totalPricefoodmon
            ]


        ];


        return $data;
    }
    public function getShiftRevenue($id) //tạm thời
    {
        $now = Carbon::now();
        $shifts = [
            'Ca 1' => ['06:00:00', '11:59:59'],
            'Ca 2' => ['12:00:00', '17:59:59'],
            'Ca 3' => ['18:00:00', '23:59:59'],
        ];

        $revenues = [];

        foreach ($shifts as $shift => $times) {
            $revenue = DB::table('book_tickets')
                ->whereTime('time', '>=', $times[0])
                ->whereTime('time', '<=', $times[1])
                ->whereDate('time', $now)
                ->where('id_cinema_details', $id)
                ->sum('amount');
            $revenues[$shift] = $revenue;
        }

        return $revenues;
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
    public function check_time_detail_by_film_id($id_cinema)
    {
        $now = now();
        $forDayLater = now()->addDays(4);
        $time_detail_by_film_id = DB::table('time_details as td')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->join('times as tms', 'tms.id', '=', 'td.time_id')
            ->where('cms.id', $id_cinema)
            ->where(function ($query) use ($now, $forDayLater) {
                $query->where('td.date', '>', $now->format('Y-m-d'))
                    ->orWhere(function ($subQuery) use ($now) {
                        $subQuery->where('td.date', '=', $now->format('Y-m-d'))
                            ->whereTime('tms.time', '>=', $now->format('H:i'));
                    });
            })
            ->select(
                'td.film_id',
                'td.id as show',
            )
            ->get();

        return $time_detail_by_film_id;
    }
}
