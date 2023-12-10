<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food_ticket_detail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use App\Models\Book_ticket;
use Carbon\Carbon;
use App\Models\Chairs;
use Pusher\Pusher;

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
            ->where('films.status', 1)
            ->whereNull('films.deleted_at')
            ->distinct()
            ->get();
        return $films;
    }
    public function categorie_detail_name()
    {
        $names = DB::table('category_details')
            ->select('film_id as id', DB::raw('GROUP_CONCAT(categories.name ORDER BY categories.name SEPARATOR ",") as category_names'))
            ->join('categories', 'category_details.category_id', '=', 'categories.id')
            ->groupBy('film_id')
            // Thêm mệnh đề GROUP BY
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
            ->whereNull('time_details.deleted_at')
            ->whereDate('time_details.date', $date)
            ->where('film_id', $filmId)
            ->get();

        return $movieRooms;
    }


    public function chair_count()
    {
        $sql = DB::table('time_details')
            ->select('id')->get();
        $arr_list_chair_count = [];

        foreach ($sql as $row) {
            $result = DB::table('movie_chairs')
                ->selectRaw('GROUP_CONCAT(name) as number')
                ->where('id_time_detail', $row->id)->whereNull('movie_chairs.deleted_at')
                ->get();

            $num = '';
            foreach ($result as $rowResult) {
                $num = $rowResult->number;
            }

            $check_length = 144 - (strlen($num) - strlen(str_replace(",", "", $num)) + 1);

            if ($num == null) {
                $check_length = 144;
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
        $currentTime = Carbon::now('Asia/Ho_Chi_Minh');
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
                    unset($seat_reservation[$request->id_time_detail][$request->id_user]['seat'][$seat]);
                    unset($seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat]);
                }
            }
            //   Thêm ghế vào cache
        } elseif (count(array_intersect($selected_seats, Arr::flatten($seat_reservation[$id_time_detail]))) === 0) {
            foreach ($selected_seats as $seat) {
                $seat_reservation[$request->id_time_detail][$request->id_user]['seat'][$seat] = $seat;
                $seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat] = $currentTime->addMinutes(2);
            }
        }
        // Đặt lại dữ liệu vào Cache
        Cache::put('seat_reservation', $seat_reservation);
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $pusher->trigger('Cinema', 'check-Seat', [
            $seat_reservation[$id_time_detail],

        ]);
        return  $seat_reservation[$id_time_detail];
        // Trả về dữ liệu ghế và thời gian đã đặt

    }

    public function getReservedSeatsByTimeDetail($id_time_detail)
    {
        $seat_reservation = Cache::get('seat_reservation', []);
        $reservedSeats = [];

        if (isset($seat_reservation[$id_time_detail])) {
            foreach ($seat_reservation[$id_time_detail] as $id_user => $userData) {
                // Lấy danh sách ghế được giữ cho mỗi người dùng
                $userSeats = $userData['seat'];

                // Thêm danh sách ghế vào danh sách ghế đã được giữ
                foreach ($userSeats as $seat) {
                    $reservedSeats[] = [
                        'seat' => $seat,
                        'id_user' => $id_user,
                        'id_time_detail' => $id_time_detail
                    ];
                }
            }
        }
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);
        $pusher->trigger(
            'Cinema',
            'SeatKepted',
            $reservedSeats,

        );

        return $reservedSeats;
        // Move the dd() here if you want to see the final value of $seat


    }


    public function purchase_history_ad()
    {
        $book_ticket_detail = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->join('members', 'members.id_user', '=', 'bt.user_id')
            ->join('films as fl', 'fl.id', '=', 'td.film_id')
            ->join('times as tm', 'tm.id', '=', 'td.time_id')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->leftJoin(DB::raw('(SELECT book_ticket_id, GROUP_CONCAT(CONCAT(quantity, " x ", name)) as food_items FROM food_ticket_details JOIN food ON food.id = food_ticket_details.food_id GROUP BY book_ticket_id) as food_ticket_details'), function ($join) {
                $join->on('food_ticket_details.book_ticket_id', '=', 'bt.id');
            })
            ->select(
                'bt.created_at as time',
                'fl.name',
                'fl.image',
                'bt.id_code',
                'bt.status',
                'members.id_card',
                'mv.name as movie_room_name',
                'cms.name as name_cinema',
                'cms.address',
                'td.date',
                'tm.time as time_suatchieu',
                'bt.amount as total_price',
                'food_ticket_details.food_items',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email',
                'users.id as user_id'
            )
            ->whereNull('bt.deleted_at')
            ->get();

        return $book_ticket_detail;
    }
    public function purchase_history_user($id)
    {

        $detail_purchase = DB::table('book_tickets as bt')
            ->join('time_details as td', 'td.id', '=', 'bt.id_time_detail')
            ->join('movie_chairs as mc', 'mc.id', '=', 'bt.id_chair')
            ->join('times', 'times.id', '=', 'td.time_id')
            ->join('users', 'users.id', '=', 'bt.user_id')
            ->join('members', 'members.id_user', '=', 'bt.user_id')
            ->join('films as fl', 'fl.id', '=', 'td.film_id')
            ->join('times as tm', 'tm.id', '=', 'td.time_id')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->leftJoin(DB::raw('(SELECT book_ticket_id, GROUP_CONCAT(CONCAT(quantity, " ", name)) as food_items FROM food_ticket_details JOIN food ON food.id = food_ticket_details.food_id GROUP BY book_ticket_id) as food_ticket_details'), function ($join) {
                $join->on('food_ticket_details.book_ticket_id', '=', 'bt.id');
            })
            ->select(
                'bt.id as id_book_ticket',
                'bt.created_at as time',
                'fl.name',
                'fl.image',
                'bt.id_code',
                'bt.status',
                'members.id_card',
                'mv.name as movie_room_name',
                'cms.name as name_cinema',
                'cms.address',

                'td.date',
                'tm.time as time_suatchieu',
                'bt.amount as total_price',
                'food_ticket_details.food_items',
                'mc.name as chair_name',
                'mc.price as chair_price',
                'users.name as users_name',
                'users.email as users_email',
                'users.id as user_id'
            )
            ->where('users.id', $id)
            ->whereNull('bt.deleted_at')
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
            )->where('btk.id_code', $id)->whereNull('ftk.deleted_at')
            ->get();
        $arr = [];
        $food_ticket_detail = $food_ticket_detail ? $food_ticket_detail : [];
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
                'fl.poster as image',
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
            ->where('id_code', '=', $id)->whereNull('bt.deleted_at')
            ->get()->first();
        if (!$book_ticket_detail) {
            return "Vé không tồn tại hoặc đã bị hủy";
        }
        return view('book_ticket_QR', ['bookTicketDetails' => [$book_ticket_detail], 'food_ticket_detail' => $arr]);
    }
    public function Revenue(Request $request)
    {
        $now = Carbon::now();
        //thống kê từ lúc bắt đầu đến hiện tại và lọc theo tháng
        $day = $request->day ?? date('d');
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $startDate = Carbon::create($year, $month, 1, 0, 0, 0);
        // Nếu không có giá trị cho tháng, sử dụng ngày cuối tháng làm ngày kết thúc, ngược lại sử dụng ngày hiện tại
        if (isset($request->month) && $request->month != $month) {
            $endDate = $startDate->copy()->endOfYears();
        } else {
            $endDate = $now;
        }
        // Tính toán doanh thu từ ngày 01 đến ngày kết thúc (cuối tháng hoặc ngày hiện tại)
        $cinemas = DB::table('cinemas')->get();
        $Revenue_on_days_in_the_month = [];
        for ($currentDate = $startDate; $currentDate->lte($endDate); $currentDate->addDay()) {
            foreach ($cinemas as $cinema) {
                $dailyRevenue = DB::table('book_tickets')
                    ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
                    ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
                    ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
                    ->whereYear('book_tickets.created_at', $year)
                    ->whereDay('book_tickets.created_at', $currentDate)
                    ->whereMonth('book_tickets.created_at', $month)
                    ->where('movie_rooms.id_cinema', $cinema->id)
                    ->where('book_tickets.status', '<>', 2)
                    ->groupBy('cinemas.id', 'cinemas.name')
                    ->select(
                        'cinemas.id as id_cinema',
                        'cinemas.name as cinema_name',
                        DB::raw('SUM(book_tickets.amount) as total_amount'),
                    )
                    ->first();
                if ($dailyRevenue) {
                    $dailyRevenues[$cinema->name] = intval($dailyRevenue->total_amount) ?? 0;
                } else {
                    $dailyRevenues[$cinema->name]  = 0;
                }
            }
            $Revenue_on_days_in_the_month[$currentDate->format('Y-m-d')] = $dailyRevenues;
            // Lưu kết quả vào mảng
        }
        $monthsWithData = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->whereYear('book_tickets.created_at', $year)
            ->where('book_tickets.status', '<>', 2)
            ->select(DB::raw('MONTH(book_tickets.created_at) as month'))
            ->groupBy(DB::raw('MONTH(book_tickets.created_at)'))
            ->pluck('month');
        $startMonth = $monthsWithData->min();
        $endMonth = $monthsWithData->max();
        $Revenue_by_cinema_in_the_month = [];
      
        for ($months = $startMonth; $months <= $endMonth; $months++) {
            foreach ($cinemas as $cinema) {
                $dailyRevenue = DB::table('book_tickets')
                    ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
                    ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
                    ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
                    ->whereYear('book_tickets.created_at', $year)
                    ->whereMonth('book_tickets.created_at', $months)
                    ->where('book_tickets.status', '<>', 2)
                    ->where('movie_rooms.id_cinema', $cinema->id)

                    ->groupBy('cinemas.id', 'cinemas.name')
                    ->select(
                        'cinemas.id as id_cinema',
                        'cinemas.name as cinema_name',
                        DB::raw('SUM(book_tickets.amount) as total_amount'),
                    )
                    ->first();
                if ($dailyRevenue) {
                    $dailyRevenues[$cinema->name] = intval($dailyRevenue->total_amount) ?? 0;
                } else {
                    $dailyRevenues[$cinema->name]  = 0;
                }
            }
            $Revenue_by_cinema_in_the_month[$year . '-' . $months] = $dailyRevenues;
            // Lưu kết quả vào mảng
        }
        // Lưu kết quả vào mảng



        $yearsWithData = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('book_tickets.status', '<>', 2)
            ->select(DB::raw('YEAR(book_tickets.created_at) as year'))
            ->groupBy(DB::raw('YEAR(book_tickets.created_at)'))
            ->pluck('year');
        $startYear = $yearsWithData->min();
        $endYear = $yearsWithData->max();
        $Revenue_by_cinema_in_the_year = [];
        for ($currentYear = $startYear; $currentYear <= $endYear; $currentYear++) {
            foreach ($cinemas as $cinema) {
                $dailyRevenue = DB::table('book_tickets')
                    ->join('movie_chairs', 'book_tickets.id_chair', '=', 'movie_chairs.id')
                    ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
                    ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
                    ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
                    ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
                    ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
                    ->whereYear('book_tickets.created_at', $year)
                    ->where('movie_rooms.id_cinema', $cinema->id)
                    ->where('book_tickets.status', '<>', 2)
                    ->groupBy('cinemas.id', 'cinemas.name')
                    ->select(
                        'cinemas.id as id_cinema',
                        'cinemas.name as cinema_name',
                        DB::raw('SUM(book_tickets.amount) as total_amount'),
                        DB::raw('SUM(movie_chairs.price) as total_chair_price'),
                        DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price')
                    )
                    ->first();
                if ($dailyRevenue) {
                    $dailyRevenues[$cinema->name] = intval($dailyRevenue->total_amount) ?? 0;
                } else {
                    $dailyRevenues[$cinema->name]  = 0;
                }
            }
            $Revenue_by_cinema_in_the_year['2023'] = $dailyRevenues;
            // Lưu kết quả vào mảng
        }
        $query =  DB::table('book_tickets')
            ->whereYear('created_at', $year);
        if ($request->month !== null) {
            $query->whereMonth('created_at', $request->month);
        }
        $revenue_month_y = $query->sum('amount');
        // Tính ngày bắt đầu của tháng

        $ticket_day =  DB::table('book_tickets')
            ->join('users', 'book_tickets.id_staff_check', '=', 'users.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->where('users.role', '<>', 0)
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('users.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))
            ->groupBy('book_tickets.id_staff_check', 'users.name')
            ->get();
        $ticket_mon =  DB::table('book_tickets')
            ->join('users', 'book_tickets.id_staff_check', '=', 'users.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('users.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))
            ->groupBy('book_tickets.id_staff_check', 'users.name')
            ->get();
        $ticket_year =  DB::table('book_tickets')
            ->join('users', 'book_tickets.id_staff_check', '=', 'users.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->where('users.role', '<>', 0)
            ->whereYear('book_tickets.created_at', $year)
            ->select('users.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))
            ->groupBy('book_tickets.id_staff_check', 'users.name')
            ->get();
        $newUsers = User::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        //-------------------------------------
        //lấy ra film có doanh thu cao nhất tháng

        $revenue_film = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->whereYear('book_tickets.created_at', $year)
            ->whereMonth('book_tickets.created_at', $month)->whereNull('book_tickets.deleted_at')
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
            ->take(5)->whereNull('book_tickets.deleted_at')
            ->get();

        //----------------------------------------------------------------
        //lấy ra tổng số vé bán ra trong tháng theo từng phim
        $book_total_mon = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as TotalTickets'))
            ->whereYear('book_tickets.created_at', $year)
            ->whereMonth('book_tickets.created_at', $month)->whereNull('book_tickets.deleted_at')
            ->groupBy('films.name')
            ->get();
        //----------------------------------------------------------------
        //lấy ra tống doanh thu từ đồ ăn theo tháng
        $totalPricefoodmon = DB::table('book_tickets')
            ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')

            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->whereNull('food_ticket_details.deleted_at')
            ->sum(DB::raw('food_ticket_details.quantity * food.price'));
        //----------------------------------------------------------------
        // lấy ra so sánh doanh thu tháng này với tháng trước

        $month2 = $month;
        $year2 = $year;

        // Lấy tháng và năm trước
        $lastMonth = $now->copy()->subMonth();
        $lastMonthNumber = $lastMonth->month;
        $lastYear = $lastMonth->year;

        // Tính toán doanh thu tháng hiện tại
        $currentMonthRevenue = DB::table('book_tickets')
            ->whereMonth('created_at', $month2)
            ->whereYear('created_at', $year2)->whereNull('book_tickets.deleted_at')
            ->sum('amount');

        // Tính toán doanh thu tháng trước
        $lastMonthRevenue = DB::table('book_tickets')
            ->whereMonth('created_at', $lastMonthNumber)
            ->whereYear('created_at', $lastYear)->whereNull('book_tickets.deleted_at')
            ->sum('amount');
        // So sánh doanh thu
        $comparison = $currentMonthRevenue - $lastMonthRevenue;
        $revenueToday = DB::table('book_tickets')
            ->whereDate('created_at', $now)->whereNull('book_tickets.deleted_at')
            ->sum('amount');
        //-------------------------------
        //lấy ra khách hàng mới trong ngày
        $user_count = User::count();
        //------------------------------------
        //lấy ra film có doanh thu cao nhất trong ngày

        $revenue_film = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->whereDate('book_tickets.created_at', $now)->whereNull('book_tickets.deleted_at')
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
            ->whereDate('book_tickets.created_at', $now)->whereNull('book_tickets.deleted_at')
            ->groupBy('films.name')
            ->get();


        $totalPricefoodday = DB::table('book_tickets')
            ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->whereNull('food_ticket_details.deleted_at')
            ->sum(DB::raw('food_ticket_details.quantity * food.price'));


        $data = [
            'user_count' => $user_count,
            "revenue_day" => [
                "revenueToday" => $revenueToday,
                'revenue_film_day' => $revenue_film,

                'book_total_day' => $book_total_day,
                'totalPricefoodday' => $totalPricefoodday,
                'ticket_day' => $ticket_day,
                'ticket_mon' => $ticket_mon,
                'ticket_year' => $ticket_year
            ],
            "revenue_month" => [
                'revenue_month_y' => $revenue_month_y,

                'revenue_film' => $revenue_film,
                'user_friendly' => $user_friendly,
                'book_total_mon' => $book_total_mon,
                'comparison' => $comparison,
                'totalPricefoodmon' => $totalPricefoodmon
            ],
            "statistical_cinema" => [
                'Revenue_by_cinema_in_the_month' => $Revenue_by_cinema_in_the_month, // biểu đồ các tháng trong năm của admin rạp
                'Revenue_by_cinema_on_the_day' => $Revenue_on_days_in_the_month, // biểu đồ các ngày trong tháng của admin rạpp
                'Revenue_by_cinema_in_the_year' => $Revenue_by_cinema_in_the_year // biểu đồ các năm của admin rạp

            ]


        ];


        return $data;
    }
    public function Revenue_cinema(Request $request)
    {
        $now = Carbon::now();
        $day = $request->day ?? date('d');
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $startDate = Carbon::create($year, $month, 1, 0, 0, 0);
        // Nếu không có giá trị cho tháng, sử dụng ngày cuối tháng làm ngày kết thúc, ngược lại sử dụng ngày hiện tại
        if (isset($request->month) && $request->month != $month) {
            $endDate = $startDate->copy()->endOfMonth();
        } else {
            $endDate = $now;
        }
        // Tính toán doanh thu từ ngày 01 đến ngày kết thúc (cuối tháng hoặc ngày hiện tại)
        $Revenue_on_days_in_the_month = [];
        for ($currentDate = $startDate; $currentDate->lte($endDate); $currentDate->addDay()) {
            // Tính toán doanh thu cho ngày hiện tại
            $dailyRevenue = DB::table('book_tickets')
                ->join('movie_chairs', 'book_tickets.id_chair', '=', 'movie_chairs.id')
                ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
                ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
                ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
                ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
                ->whereYear('book_tickets.created_at', $year)
                ->whereMonth('book_tickets.created_at', $month)
                ->where('movie_rooms.id_cinema', $request->id_cinema)
                ->whereDate('book_tickets.created_at', $currentDate)
                ->where('book_tickets.status', '<>', 2)
                ->select(
                    DB::raw('SUM(book_tickets.amount) as total_amount'),
                    DB::raw('SUM(movie_chairs.price) as total_price'),
                    DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price')
                )
                ->get();
            // Lưu kết quả vào mảng
            $Revenue_on_days_in_the_month[$currentDate->format('Y-m-d')] = [
                'total_amount' => intval($dailyRevenue->first()->total_amount) ?? 0,
                'total_chair_price' => intval($dailyRevenue->first()->total_amount - $dailyRevenue->first()->total_food_price) ?? 0,
                'total_food_price' => intval($dailyRevenue->first()->total_food_price) ?? 0,
            ];
        }
        for ($month = 1; $month <= 12; $month++) {
            // Tính ngày bắt đầu và kết thúc của tháng

            // Tính toán doanh thu cho tháng hiện tại
            $monthlyRevenue = DB::table('book_tickets')
                ->join('movie_chairs', 'book_tickets.id_chair', '=', 'movie_chairs.id')
                ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
                ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
                ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
                ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
                ->whereYear('book_tickets.created_at', $year)
                ->whereMonth('book_tickets.created_at', $month)
                ->where('movie_rooms.id_cinema', $request->id_cinema)
                ->where('book_tickets.status', '<>', 2)

                ->select(
                    DB::raw('SUM(book_tickets.amount) as total_amount'),
                    DB::raw('SUM(movie_chairs.price) as total_price'),
                    DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price')
                )
                ->get();
            // Lưu kết quả vào mảng
            $Revenue_in_months_of_the_year[$year . '-' . $month] = [
                'total_amount' => intval($monthlyRevenue->first()->total_amount) ?? 0,
                'total_chair_price' => intval($monthlyRevenue->first()->total_amount - $monthlyRevenue->first()->total_food_price) ?? 0,
                'total_food_price' => intval($monthlyRevenue->first()->total_food_price) ?? 0,
            ];
        }
        $yearsWithData = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('cinemas.id', $request->id_cinema)
            ->where('book_tickets.status', '<>', 2)

            ->select(DB::raw('YEAR(book_tickets.created_at) as year'))
            ->groupBy(DB::raw('YEAR(book_tickets.created_at)'))
            ->pluck('year');

        // Gán giá trị của năm đầu tiên và năm cuối cùng
        $startYear = $yearsWithData->min();
        $endYear = $yearsWithData->max();
        $Revenue_by_year = [];
        for ($currentYear = $startYear; $currentYear <= $endYear; $currentYear++) {
            // Tính toán doanh thu cho năm hiện tại
            $yearlyRevenue = DB::table('book_tickets')
                ->join('movie_chairs', 'book_tickets.id_chair', '=', 'movie_chairs.id')
                ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
                ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
                ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
                ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
                ->whereYear('book_tickets.created_at', $currentYear)
                ->where('movie_rooms.id_cinema', $request->id_cinema)
                ->where('book_tickets.status', '<>', 2)
                ->select(
                    DB::raw('SUM(book_tickets.amount) as total_amount'),
                    DB::raw('SUM(movie_chairs.price) as total_price'),
                    DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price')
                )
                ->get();
            // Lưu kết quả vào mảng
            $Revenue_by_year[] = [
                'total_amount' => intval($yearlyRevenue->first()->total_amount) ?? 0,
                'total_chair_price' => intval($yearlyRevenue->first()->total_price) ?? 0,
                'total_food_price' => intval($yearlyRevenue->first()->total_food_price) ?? 0,
            ];
        }

        $day = intval($request->day ?? date('d'));
        $year = intval($request->year ?? date('Y'));
        $month = intval($request->month ?? date('m'));
        /// lấy ra doanh thu 1 ngày theo rạp cho nhân viên xem
        $revenue_staff_day = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('book_tickets.status', '<>', 2)

            ->where('cinemas.id', $request->id_cinema)
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('cinemas.name as cinema_name', DB::raw('SUM(book_tickets.amount) as total_amount'))
            ->groupBy('cinemas.name')
            ->get();
        // lọc theo ngày 


        ///số vé bán ra theo từng tên phim của một ngày
        $tickets_total_day = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))

            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->where('cinemas.id', $request->id_cinema)
            ->groupBy('films.name')
            ->get();
        ///lấy ra tổng doanh thu theo ngày từ đồ ăn
        $revenue_food =  DB::table('book_tickets')
            ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('book_tickets.status', '<>', 2)
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->where('cinemas.id', $request->id_cinema)
            ->sum('food.price');


        //lọc ra doanh thu ngày tháng năm hiện tại theo rạp (admin)
        $revenue_admin_day_filter = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('book_tickets.status', '<>', 2)
            ->where('cinemas.id', $request->id_cinema)
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('cinemas.name as cinema_name', DB::raw('SUM(book_tickets.amount) as total_amount'))
            ->groupBy('cinemas.name')
            ->get();

        //lọc ra doanh thu tháng  hiện tại theo rạp (admin)
        $revenue_admin_mon_filter = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('book_tickets.status', '<>', 2)

            ->where('cinemas.id', $request->id_cinema)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('cinemas.name as cinema_name', DB::raw('SUM(book_tickets.amount) as total_amount'))
            ->groupBy('cinemas.name')
            ->get();
        //lọc ra doanh thu năm  hiện tại theo rạp (admin)
        $revenue_admin_year_filter = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('book_tickets.status', '<>', 2)
            ->where('cinemas.id', $request->id_cinema)
            ->whereYear('book_tickets.created_at', $year)
            ->select('cinemas.name as cinema_name', DB::raw('SUM(book_tickets.amount) as total_amount'))
            ->groupBy('cinemas.name')
            ->get();


        //lấy ra doanh thu ngày tháng năm hiện tại theo rạp tự động (admin)


        ///số vé bán ra theo từng tên phim của một ngày
        $tickets_total_mon = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))
            ->where('book_tickets.status', '<>', 2)

            ->whereMonth('book_tickets.created_at', '=', $month)
            ->whereYear('book_tickets.created_at', '=', $year)
            ->where('cinemas.id', $request->id_cinema)
            ->groupBy('films.name')
            ->get();

        $ticket_staff_fill_day =  DB::table('book_tickets')
            ->join('users', 'book_tickets.id_staff_check', '=', 'users.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->where('users.role', '<>', 0)
            ->where('cinemas.id', $request->id_cinema)
            ->whereDay('book_tickets.created_at', $day)
            ->where('book_tickets.status', '<>', 2)

            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('users.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))
            ->groupBy('book_tickets.id_staff_check', 'users.name')
            ->get();

        $ticket_staff_fill_mon =  DB::table('book_tickets')
            ->join('users', 'book_tickets.id_staff_check', '=', 'users.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->where('users.role', '<>', 0)
            ->where('book_tickets.status', '<>', 2)

            ->where('cinemas.id', $request->id_cinema)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select('users.name', DB::raw('COUNT(book_tickets.id) as total_tickets'))
            ->groupBy('book_tickets.id_staff_check', 'users.name')
            ->get();

        $total_food_mon = DB::table('book_tickets')
            ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')

            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->whereMonth('book_tickets.created_at', $month)
            ->where('book_tickets.status', '<>', 2)

            ->whereYear('book_tickets.created_at', $year)
            ->where('cinemas.id', $request->id_cinema)
            ->sum('food.price');
        $total_food_year = DB::table('book_tickets')
            ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->whereYear('book_tickets.created_at', $year)
            ->where('book_tickets.status', '<>', 2)

            ->where('cinemas.id', $request->id_cinema)
            ->sum('food.price');
        $refund_ticket_day = DB::table('book_tickets')
            ->where('book_tickets.status', 2)
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('cinemas.id', $request->id_cinema)
            ->whereDay('book_tickets.created_at', $day)

            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select(DB::raw('count(*) as ticket_count, 0.3 * sum(book_tickets.amount) as total_amount'))
            ->get();
        $refund_ticket_month = DB::table('book_tickets')
            ->where('book_tickets.status', 2)
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('cinemas.id', $request->id_cinema)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->select(DB::raw('count(*) as ticket_count,  0.3 *sum(book_tickets.amount) as total_amount'))
            ->get();
        $refund_ticket_year = DB::table('book_tickets')
            ->where('book_tickets.status', 2)
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('cinemas.id', $request->id_cinema)
            ->whereYear('book_tickets.created_at', $year)
            ->select(DB::raw('count(*) as ticket_count,  0.3 *sum(book_tickets.amount) as total_amount'))
            ->get();
        $top_5_films = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')

            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->select('films.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->where('cinemas.id', $request->id_cinema)
            ->whereYear('book_tickets.created_at', $year)
            ->whereMonth('book_tickets.created_at', $month)->whereNull('book_tickets.deleted_at')
            ->groupBy('films.name')
            ->orderBy('TotalAmount', 'desc')
            ->take(5)
            ->get();

        // Lấy giá trị và đặt vào mảng

        return [
            "revenue_staff" => [ // thông tin cho nhân viên xem 
                'revenue_staff_day' => $revenue_staff_day, // thống kê 
                'tickets_total_day' => $tickets_total_day, // xem tên nhân viên và số lượng vé check-in
                'revenue_food' => $revenue_food // thống kê đồ ăn
            ],
            "revenue_admin_cinema" => [  // thông tin cho admin rạpp xem, nếu truyền day vào thì sẽ tính theo tháng và năm hiện tại 
                // ví dụ chuyền vào 5 thì sẽ tính theo 2023/12/09, nếu k chuyền thì sẽ tính theo thời gian hiện tại
                //  Tương tự tháng và năm như vậy, chỉ truyền vào tháng sẽ tính theo ngày và năm hiện tại, chỉ có số tháng thay đổi.
                //  ví dụ nhập month = 7 thì sẽ tính 2023/07/09
                // nếu không ra dữ liệu ngày có nghĩa là ngày đó k có dữ liệu
                'revenue_admin_day_filter' => $revenue_admin_day_filter,    //thống kê ngày của rạp
                'revenue_admin_mon_filter' => $revenue_admin_mon_filter,    // thống kê tháng của rạp
                'revenue_admin_year_filter' => $revenue_admin_year_filter,  // thống kê năm của rạp
                'tickets_total_day' => $tickets_total_day,              // số lượng vé của rạp
                'tickets_total_mon' => $tickets_total_mon,
                'ticket_staff_fill_day' => $ticket_staff_fill_day,      // số lượng vé check của nhân viên.
                'ticket_staff_fill_mon' => $ticket_staff_fill_mon,
                'revenue_food_day' => $revenue_food,        // tổng doanh thu đồ ăn
                'revenue_food_day' => $revenue_food,        // tổng doanh thu đồ ăn
                'total_food_mon' => $total_food_mon,
                'total_food_year' => $total_food_year,
                'refund_ticket_day' => $refund_ticket_day,  // vé hoàn trong ngày
                'refund_ticket_month' => $refund_ticket_month,  // vé hoàn tháng
                'refund_ticket_year' => $refund_ticket_year, // vé hoàn năm
                'top_5_films' => $top_5_films // top 5 phim
            ],
            "statistical_cinema" => [
                'Revenue_in_months_of_the_year' => $Revenue_in_months_of_the_year, // biểu đồ các tháng trong năm của admin rạp
                'Revenue_on_days_in_the_month' => $Revenue_on_days_in_the_month, // biểu đồ các ngày trong tháng của admin rạpp
                'Revenue_by_year' => $Revenue_by_year // biểu đồ các năm của admin rạp

            ]
        ];
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
            ->whereNull('cinemas.deleted_at')
            ->first();
        return $CinemaDetailbyId;
    }
    public function check_time_detail_by_film_id(Request $request, $id_cinema)
    {
        $now = now(); // Assuming $now is already defined
        $time_detail_by_film_id = DB::table('time_details as td')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->join('times as tms', 'tms.id', '=', 'td.time_id')
            ->where('cms.id', $id_cinema)
            ->where(function ($query) use ($now) {
                $query->where('td.date', '>', $now->format('Y-m-d'))
                    ->orWhere(function ($subQuery) use ($now) {
                        $subQuery->where('td.date', '=', $now->format('Y-m-d'))
                            ->whereTime('tms.time', '>=', $now->format('H:i'));
                    });
            })
            ->whereNull('td.deleted_at')
            ->whereBetween('td.date', [$now->format('Y-m-d'), $now->addDays(4)->format('Y-m-d')])
            ->select(
                'td.film_id',
                'td.id as show',
            )
            ->get();
        return $time_detail_by_film_id;
    }
    public function check_time_detail_by_film(Request $request)
    {
        $now = now();
        $now = now(); // Assuming $now is already defined
        $time_detail_by_film_id = DB::table('time_details as td')
            ->join('movie_rooms as mv', 'mv.id', '=', 'td.room_id')
            ->join('cinemas as cms', 'cms.id', '=', 'mv.id_cinema')
            ->join('times as tms', 'tms.id', '=', 'td.time_id')
            ->where('cms.id', $request->id_cinema)

            ->where(function ($query) use ($now) {
                $query->where('td.date', '>', $now->format('Y-m-d'))
                    ->orWhere(function ($subQuery) use ($now) {
                        $subQuery->where('td.date', '=', $now->format('Y-m-d'))
                            ->whereTime('tms.time', '>=', $now->format('H:i'));
                    });
            })
            ->whereNull('td.deleted_at')
            ->where('film_id', $request->film_id)
            ->whereBetween('td.date', [$now->format('Y-m-d'), $now->addDays(4)->format('Y-m-d')])
            ->select(
                'td.film_id',
                'td.id as show',
            )
            ->get();
        return $time_detail_by_film_id;
    }
    public function get_room_by_id_cinema(Request $request, $id)
    {
        $movie_rooms = DB::table('movie_rooms')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->where('movie_rooms.id_cinema', $id)
            ->select(
                'movie_rooms.*',
                'cinemas.name as name_cinema'
            )
            ->get();

        return $movie_rooms;
    }
    public function get_used_vouchers_by_id_user($id)
    {
        $data = DB::table('used_vouchers as uv')
            ->where('user_id', $id)
            ->select('uv.*')->get();
        return $data;
    }
    public function refund_coin(Request $request, $id)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $startOfMonth = Carbon::now()->startOfMonth();
        // Lấy ngày cuối cùng của tháng hiện tại
        $endOfMonth = Carbon::now()->endOfMonth();
        // Sử dụng whereBetween để xác định khoảng thời gian
        $status = Book_ticket::find($id);
        $check_time = DB::table('time_details')->join('times', 'time_details.time_id', '=', 'times.id')
            ->get()->first();
        $dateTimeString = $check_time->date . ' ' . $check_time->time;
        $check = Book_ticket::where('user_id', $status->user_id)
            ->whereBetween('time', [$startOfMonth, $endOfMonth])
            ->where('status', 2)
            ->count();
        if ($check >= 2) {
            return response([
                'msg' => 'Bạn đã hủy tối đa trong tháng này !',
            ], 403);
        }
        // Tạo đối tượng Carbon từ chuỗi datetime
        $dateTime = Carbon::parse($dateTimeString);
        // Chuyển đổi thành timestamp

        // So sánh với thời điểm hiện tại
        $twoHoursAgo = $dateTime->subHours(2);
        if ($status && !$now->gte($twoHoursAgo)) {
            $refund_coins = User::find($status->user_id);
            if (Hash::check($request->input('password'), $refund_coins->password)) {
                if (!$status) {
                    return response([
                        'message' => 'Vé không tồn tại !',
                    ], 403);
                }
                $cancel_chair = Chairs::find($status->id_chair);
                if (!$cancel_chair) {
                    return response([
                        'message' => 'Ghế không tồn tại hoặc đã hủy !',
                    ], 403);
                }
                $cancel_chair->delete();
                $update = $status->update(['status' => 2]);
                $coin_usage = $refund_coins->coin;
                $amount = intval(($status->amount *= 0.7)) + $coin_usage;
                $refund_coins->update(['coin' => $amount]);
                return response()->json(['message' => "Hủy thành công, số coin " . intval($status->amount *= 0.7) . " đã được hoàn vào ví coin của bạn"], 200);
            } else {
                return response()->json(['message' => 'Nhập sai mật khẩu, vui lòng thử lại!'], 403);
            }
        } else {
            return response()->json(['message' => 'Vé không tồn tại hoặc đã quá thời gian hủy vé!'], 403);
        }
    }
}