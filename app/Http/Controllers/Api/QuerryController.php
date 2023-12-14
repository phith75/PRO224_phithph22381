<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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
                $seat_reservation[$request->id_time_detail][$request->id_user]['time'][$seat] = $currentTime->addMinutes(1);
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
    public function purchase_history_check($status= []){
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
        ->leftJoin('users as staff', 'staff.id', '=', 'bt.id_staff_check')
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
            'users.id as user_id',
            'staff.name as staff_name' // Thêm cột để lấy tên của người kiểm tra
        )   
        ->orderBy('bt.created_at', 'desc')
        ->whereNull('bt.deleted_at')
        ->get();
        return $book_ticket_detail;
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
            ->leftJoin('users as staff', 'staff.id', '=', 'bt.id_staff_check')
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
                'users.id as user_id',
                'staff.name as staff_name' // Thêm cột để lấy tên của người kiểm tra
            )   
            ->orderBy('bt.created_at', 'desc')
            ->whereNull('bt.deleted_at')
            ->get();
        
    
            return $book_ticket_detail;
        }
    
    public function purchase_history_ad_refund()
    {
      return $this->purchase_history_check([2,3]);
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
        ->orderBy('bt.created_at', 'desc')

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
        ->where('time_details.id',$status->id_time_detail)
            ->get()->first();
        $dateTimeString = $check_time->date . ' ' . $check_time->time;
        $check = Book_ticket::where('user_id', $status->user_id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 2)
            ->count();
        if ($check >= 2) {
            return response([
                'message' => 'Bạn đã hủy tối đa trong tháng này !',
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