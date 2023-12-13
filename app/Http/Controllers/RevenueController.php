<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function Revenue(Request $request)
    {
        $now = Carbon::now();  
        //thống kê từ lúc bắt đầu đến hiện tại và lọc theo tháng
        $day = $request->day ?? date('d');
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $startDate = Carbon::create($year, $month, 1, 0, 0, 0);
        // Nếu không có giá trị cho tháng, sử dụng ngày cuối tháng làm ngày kết thúc, ngược lại sử dụng ngày hiện tại
        if (isset($request->month) && $request->month != date('m')) {
            $endDate = $startDate->copy()->endOfMonth();
        } else {
            $endDate = $now;
        }
        // Tính toán doanh thu từ ngày 01 đến ngày kết thúc (cuối tháng hoặc ngày hiện tại)
        $cinemas = DB::table('cinemas')->get();
        $Revenue_on_days_in_the_month = [];
        for ($currentDate = $startDate; $currentDate->lte($endDate); $currentDate->addDay()) {
            foreach ($cinemas as $cinema) {
                $dailyRevenue = DB::table('book_tickets')
                ->join('movie_chairs', 'book_tickets.id_chair', '=', 'movie_chairs.id')
                ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
                ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
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
                        DB::raw('SUM(movie_chairs.price) as total_chair_price'),
                        DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price')
                    )
                    ->first();
                if ($dailyRevenue) {
                    $dailyRevenues[$cinema->name] = [
                        'total_amount' => intval($dailyRevenue->total_amount) ?? 0,
                        'total_chair_price' => intval($dailyRevenue->total_chair_price) ?? 0,
                        'total_food_price' => intval($dailyRevenue->total_food_price) ?? 0,
                    ];
                } else {
                    $dailyRevenues[$cinema->name]  = [
                        'total_amount' => 0,
                        'total_chair_price' => 0,
                        'total_food_price' =>  0,
                    ];
            }
            $Revenue_on_days_in_the_month[$currentDate->format('Y-m-d')] = $dailyRevenues;
            // Lưu kết quả vào mảng
        }}
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
        for ($months = 1; $months <= 12; $months++) {
            foreach ($cinemas as $cinema) {
                $dailyRevenue = DB::table('book_tickets')
                ->join('movie_chairs', 'book_tickets.id_chair', '=', 'movie_chairs.id')
                ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
                ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
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
                        DB::raw('SUM(movie_chairs.price) as total_chair_price'),
                        DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price')
                    )
                    ->first();
                if ($dailyRevenue) {
                    $dailyRevenues[$cinema->name] = [
                        'total_amount' => intval($dailyRevenue->total_amount) ?? 0,
                        'total_chair_price' => intval($dailyRevenue->total_chair_price) ?? 0,
                        'total_food_price' => intval($dailyRevenue->total_food_price) ?? 0,
                    ];
                } else {
                    $dailyRevenues[$cinema->name]  = [
                        'total_amount' => 0,
                        'total_chair_price' => 0,
                        'total_food_price' =>  0,
                    ];
                    }
            }
            $Revenue_by_cinema_in_the_month[$year . '-' . $months] = $dailyRevenues;
            // Lưu kết quả vào mảng
        }
        // Lưu kết quả vào mảng

 $day = $request->day ?? date('d');
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

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
                    $dailyRevenues[$cinema->name] = [
                        'total_amount' => intval($dailyRevenue->total_amount) ?? 0,
                        'total_chair_price' => intval($dailyRevenue->total_chair_price) ?? 0,
                        'total_food_price' => intval($dailyRevenue->total_food_price) ?? 0,
                    ];
                } else {
                    $dailyRevenues[$cinema->name]  = [
                        'total_amount' => 0,
                        'total_chair_price' => 0,
                        'total_food_price' =>  0,
                    ];
                }
            }
            $Revenue_by_cinema_in_the_year['2023'] = $dailyRevenues;
            // Lưu kết quả vào mảng
        }
        $query =  DB::table('book_tickets')
            ->whereYear('created_at', $year);
        if ($request->month !== null) {
            $query->whereMonth('created_at', $month);
        }
        $revenue_month_y = $query->sum('amount');
        // Tính ngày bắt đầu của tháng
        $today = now(); 
        $released_film_num = DB::table('films')
        ->where('release_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->count();
        $count_food = DB::table('food')->count();
        $count_cinema =  DB::table('cinemas')->where('status',1)->count();
        $ticket_day =  DB::table('book_tickets')
        ->join('users', 'book_tickets.id_staff_check', '=', 'users.id')
        ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
        ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
        ->join('cinemas', 'movie_rooms.id_cinema', '=', 'cinemas.id')
        ->join('films', 'time_details.film_id', '=', 'films.id')
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
        $ticket_year = DB::table('book_tickets')
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

        $revenue_film_month = DB::table('book_tickets')
        ->leftJoin('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
        ->leftJoin('films', 'time_details.film_id', '=', 'films.id')
        ->leftJoin('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
        ->leftJoin('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
        ->select('films.name as film_name', 
                 DB::raw('ROUND(SUM(CASE WHEN book_tickets.status <> 2 THEN book_tickets.amount ELSE 0 END) + SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as TotalAmount'),
                 DB::raw('COUNT(CASE WHEN book_tickets.status <> 2 THEN book_tickets.id ELSE NULL END) as TotalTickets'),
                 DB::raw('ROUND(SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as RefundAmount'),
                 DB::raw('COUNT(CASE WHEN book_tickets.status = 2 THEN 1 ELSE NULL END) as RefundTickets'))
        ->whereMonth('book_tickets.created_at', $month)
        ->whereYear('book_tickets.created_at', $year)
        ->whereNull('book_tickets.deleted_at')
        ->groupBy('films.name')
        ->orderBy('TotalAmount', 'desc')
        ->get();

        //----------------------------------------------------------------
        //lấy ra 5 khách hàng thân thiết
        $user_friendly = DB::table('book_tickets')
            ->join('users', 'book_tickets.user_id', '=', 'users.id')
            ->join('members', 'members.id_user', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(book_tickets.amount) as TotalAmount'),'members.card_class')
            ->groupBy('users.name','members.card_class')
            ->orderBy('TotalAmount', 'desc')
            ->take(5)->whereNull('book_tickets.deleted_at')
            ->get();

        //----------------------------------------------------------------
        //lấy ra tổng số vé bán ra trong tháng theo từng phim
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
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)->whereNull('book_tickets.deleted_at')
            ->sum('amount');
        //-------------------------------
        //lấy ra khách hàng mới trong ngày
        $user_count = User::count();
        //------------------------------------
        //lấy ra film có doanh thu cao nhất trong ngày

        $revenue_film = DB::table('book_tickets')
    ->leftJoin('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
    ->leftJoin('films', 'time_details.film_id', '=', 'films.id')
    ->leftJoin('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
    ->leftJoin('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
    ->select('films.name as film_name', 
             DB::raw('ROUND(SUM(CASE WHEN book_tickets.status <> 2 THEN book_tickets.amount ELSE 0 END) + SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as TotalAmount'),
             DB::raw('COUNT(CASE WHEN book_tickets.status <> 2 THEN book_tickets.id ELSE NULL END) as TotalTickets'),
             DB::raw('ROUND(SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as RefundAmount'),
             DB::raw('COUNT(CASE WHEN book_tickets.status = 2 THEN 1 ELSE NULL END) as RefundTickets'))
    ->whereDay('book_tickets.created_at', $day)
    ->whereMonth('book_tickets.created_at', $month)
    ->whereYear('book_tickets.created_at', $year)
    ->whereNull('book_tickets.deleted_at')
    ->groupBy('films.name')
    ->orderBy('TotalAmount', 'desc')
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
            "released_film_num" => $released_film_num,
            "count_food" => $count_food,
            "count_cinema" => $count_cinema,
            'user_count' => $user_count,
            "revenue_day" => [
                "revenueToday" => $revenueToday,
                'revenue_and_refund_day' => $revenue_film,
                'totalPricefoodday' => $totalPricefoodday,
                'ticket_day' => $ticket_day,
                'ticket_mon' => $ticket_mon,
                'ticket_year' => $ticket_year
            
            ],
            "revenue_month" => [
                'revenue_month_y' => $revenue_month_y,
                'revenue_and_refund_month' => $revenue_film_month,
                'user_friendly' => $user_friendly,
                'comparison' => $comparison,
                'totalPricefoodmon' => $totalPricefoodmon
            ],
            "statistical_cinema" => [
                'Revenue_by_cinema_in_the_month' => $Revenue_by_cinema_in_the_month, // biểu đồ các tháng trong năm của admin rạp
                'Revenue_by_cinema_on_the_day' => $Revenue_on_days_in_the_month, // biểu đồ các ngày trong tháng của admin rạpp
                'Revenue_by_cinema_in_the_year' => $Revenue_by_cinema_in_the_year // biểu đồ các năm của admin rạp

            ],
          
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
        if (isset($request->month) && $request->month != date('m')) {
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
        // lọc theo ngày 
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
       
        ///số vé bán ra theo từng tên phim của một ngày
        $tickets_total_day = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as total_tickets'),DB::raw('SUM(book_tickets.amount) as TotalAmount'))
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
            ->select(DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price'))
            ->get()->first();
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
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as total_tickets'),DB::raw('SUM(book_tickets.amount) as TotalAmount'))
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
            ->select(DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price'))
            ->get()->first();
           
        $total_food_year = DB::table('book_tickets')
            ->join('food_ticket_details', 'book_tickets.id', '=', 'food_ticket_details.book_ticket_id')
            ->join('food', 'food_ticket_details.food_id', '=', 'food.id')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->whereYear('book_tickets.created_at', $year)
            ->where('book_tickets.status', '<>', 2)
            ->where('cinemas.id', $request->id_cinema)
            ->select(DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price'))
            ->get()->first();

            $revenue_film = DB::table('book_tickets')
            ->leftJoin('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->leftJoin('films', 'time_details.film_id', '=', 'films.id')
            ->leftJoin('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->leftJoin('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->select('films.name as film_name', 
                     DB::raw('ROUND(SUM(CASE WHEN book_tickets.status <> 2 THEN book_tickets.amount ELSE 0 END) + SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as TotalAmount'),
                     DB::raw('COUNT(CASE WHEN book_tickets.status <> 2 THEN book_tickets.id ELSE NULL END) as TotalTickets'),
                     DB::raw('ROUND(SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as RefundAmount'),
                     DB::raw('COUNT(CASE WHEN book_tickets.status = 2 THEN 1 ELSE NULL END) as RefundTickets'))
            ->where('cinemas.id', $request->id_cinema)
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->whereNull('book_tickets.deleted_at')
            ->groupBy('films.name')
            ->orderBy('TotalAmount', 'desc')
            ->get();

            $revenue_film_month = DB::table('book_tickets')
            ->leftJoin('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->leftJoin('films', 'time_details.film_id', '=', 'films.id')
            ->leftJoin('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->leftJoin('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->select('films.name as film_name', 
                     DB::raw('ROUND(SUM(CASE WHEN book_tickets.status <> 2 THEN book_tickets.amount ELSE 0 END) + SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as TotalAmount'),
                     DB::raw('COUNT(CASE WHEN book_tickets.status <> 2 THEN book_tickets.id ELSE NULL END) as TotalTickets'),
                     DB::raw('ROUND(SUM(CASE WHEN book_tickets.status = 2 THEN 0.3 * book_tickets.amount ELSE 0 END), 0) as RefundAmount'),
                     DB::raw('COUNT(CASE WHEN book_tickets.status = 2 THEN 1 ELSE NULL END) as RefundTickets'))
            ->where('cinemas.id', $request->id_cinema)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->whereNull('book_tickets.deleted_at')
            ->groupBy('films.name')
            ->orderBy('TotalAmount', 'desc')
            ->get();

      

        // Lấy giá trị và đặt vào mảng

        return [
            "revenue_admin_cinema" => [  // thông tin cho admin rạpp xem, nếu truyền day vào thì sẽ tính theo tháng và năm hiện tại 
                // ví dụ chuyền vào 5 thì sẽ tính theo 2023/12/09, nếu k chuyền thì sẽ tính theo thời gian hiện tại
                //  Tương tự tháng và năm như vậy, chỉ truyền vào tháng sẽ tính theo ngày và năm hiện tại, chỉ có số tháng thay đổi.
                //  ví dụ nhập month = 7 thì sẽ tính 2023/07/09
                // nếu không ra dữ liệu ngày có nghĩa là ngày đó k có dữ liệu
                'revenue_admin_day_filter' => $revenue_admin_day_filter,    //thống kê ngày của rạp
                'revenue_admin_mon_filter' => $revenue_admin_mon_filter,    // thống kê tháng của rạp
                'revenue_admin_year_filter' => $revenue_admin_year_filter,  // thống kê năm của rạp
                'revenue_and_refund_day_cinema' => $revenue_film,  //
                'revenue_and_refund_month_cinema' => $revenue_film_month,
                'ticket_staff_fill_day' => $ticket_staff_fill_day,      // số lượng vé check của nhân viên.
                'ticket_staff_fill_mon' => $ticket_staff_fill_mon,
                'revenue_food_day' => $revenue_food->total_food_price,        // tổng doanh thu đồ ăn
                'total_food_mon' => $total_food_mon->total_food_price,
                'total_food_year' => $total_food_year->total_food_price,
                
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
    public function Revenue_cinema_staff(Request $request){
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
            $tickets_total_day = DB::table('book_tickets')
            ->join('time_details', 'book_tickets.id_time_detail', '=', 'time_details.id')
            ->join('films', 'time_details.film_id', '=', 'films.id')
            ->join('movie_rooms', 'time_details.room_id', '=', 'movie_rooms.id')
            ->join('cinemas', 'cinemas.id', '=', 'movie_rooms.id_cinema')
            ->select('films.name', DB::raw('COUNT(book_tickets.id) as total_tickets'),DB::raw('SUM(book_tickets.amount) as TotalAmount'))
            ->whereDay('book_tickets.created_at', $day)
            ->whereMonth('book_tickets.created_at', $month)
            ->whereYear('book_tickets.created_at', $year)
            ->where('cinemas.id', $request->id_cinema)
            ->groupBy('films.name')
            ->get();
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
            ->select(DB::raw('SUM(food_ticket_details.quantity * food.price) as total_food_price'))
            ->get()->first();
        return ["revenue_staff" => [ // thông tin cho nhân viên xem 
                'revenue_staff_day' => $revenue_staff_day, // thống kê 
                'tickets_total_day' => $tickets_total_day, // xem tên nhân viên và số lượng vé check-in
                'revenue_food' => $revenue_food // thống kê đồ ăn
                ] ];
    }
}
