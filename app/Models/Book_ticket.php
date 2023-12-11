<?php

namespace App\Models;

use Pusher\Pusher;
use App\Models\Chairs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Book_ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "book_tickets"; // phải điền đúng tên bảng mà mình cần trỏ tới trong 
    protected $fillable = ['id', 'id_time_detail', 'user_id', 'status','payment', 'amount','time' ,'id_chair','id_code','id_staff_check'];
    protected static function boot()
    {
        parent::boot();
        static::created(function ($bookTicket) {
            $bookTicket->updateMemberTotalSpending();
            // $bookTicket->deletecache_seat();
        });
    }

    public function updateMemberTotalSpending()
    {   
        $member = Member::where('id_user', $this->user_id)->first();
        if ($member) {
            $percent = 100;
            if($member->card_class == 2){
            $percent = 20;
            }
            $usable_speding = $member->usable_points + $this->amount / $percent;
            $newTotalSpending = $member->total_spending + $this->amount;
            $member->update(['total_spending' => $newTotalSpending, 'usable_points' => $usable_speding]);
        }
    }
//     public function deletecache_seat(){
//         $seat_reservation = Cache::get('seat_reservation', []);
//         $chair = Chairs::where('id', $this->id_chair)->first();
//         $selected_seats = explode(',', $chair->name);
//         $id_time_detail = $this->id_time_detail;
//         $seat_reservation[$id_time_detail][$this->user_id] ??= [
//             'seat' => [],
//             'time' => [],
//         ];
//         // Kiểm tra ghế đã được đặt
//         if (
//             in_array($this->user_id, array_keys($seat_reservation[$id_time_detail])) &&
//             count(array_intersect($selected_seats, $seat_reservation[$id_time_detail][$this->user_id]['seat'])) > 0
//         )
//             {
//             foreach ($selected_seats as $seat) {
                
//             $index = array_search($seat, $seat_reservation[$id_time_detail][$this->user_id]['seat']);
//             if ($index !== false) {
//                 unset($seat_reservation[$id_time_detail][$this->user_id]['seat'][$seat]);
//                 unset($seat_reservation[$id_time_detail][$this->user_id]['time'][$seat]);
//             }
//         }
//         if (isset($seat_reservation[$id_time_detail])) {
//             foreach ($seat_reservation[$id_time_detail] as $id_user => $userData) {
//                 // Lấy danh sách ghế được giữ cho mỗi người dùng
//                 $userSeats = $userData['seat'];
//                 // Thêm danh sách ghế vào danh sách ghế đã được giữ
//                 foreach ($userSeats as $seat) {
//                     $reservedSeats[] = [
//                         'seat' => $seat,
//                         'id_user' => $id_user,
//                         'id_time_detail' => $id_time_detail
//                     ];
//                 }
//             }
//         }
//     }
//     Cache::put('seat_reservation', $seat_reservation);
//         $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
//             'cluster' => env('PUSHER_APP_CLUSTER'),
//             'useTLS' => true,
//         ]);
//         $pusher->trigger(
//             'Cinema',
//             'SeatKepted',
//             $reservedSeats,
//         );
// }
}
