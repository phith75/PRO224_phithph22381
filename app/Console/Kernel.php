<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Book_ticket;
use Carbon\Carbon;
use App\Models\Chairs;
use Pusher\Pusher;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->call(function () {
            $this->clearExpiredSeats();
        })->everyMinute();
        $schedule->call(function () {
            $this->checkTimeBookTicket();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
    public function clearExpiredSeats()
    {   
        $currentTime = Carbon::now('Asia/Ho_Chi_Minh');
        $seat_reservation = Cache::get('seat_reservation', []);

    // Kiểm tra xem mảng có trống không hay không

        // Kiểm tra xem mảng có trống không hay không
        if (!empty($seat_reservation)) {
            foreach ($seat_reservation as $id_time_detail => &$users) {
                foreach ($users as $id_user => &$data) {
                    foreach ($data['time'] as $seat => $carbonObject) {
                        // Extract timestamp from Carbon object
                        $timestamp = $carbonObject->timestamp;
                        // Calculate the difference in seconds
                        $secondsDifference = $currentTime->diffInSeconds($carbonObject);
                        // Nếu số giây vượt quá 1 phút, xóa thông tin ghế hết hạn
                        if ($secondsDifference >= 120) {
                            unset($data['seat'][$seat]);
                            unset($data['time'][$seat]);
                            Cache::put('seat_reservation', $seat_reservation);
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
                            
                        }
                    }
                }
            }
            // Gán lại mảng đã thay đổi cho biến Cache
          
        }
        
    }
    public function checkTimeBookTicket()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        
        $bookTickets = Book_ticket::where('status', 0)
            ->get();
        foreach ($bookTickets as $bookTicket) {
            $check_time = DB::table('time_details')
                ->join('times', 'time_details.time_id', '=', 'times.id')
                ->where('time_details.id', $bookTicket->id_time_detail)
                ->select('times.time','time_details.date')
                ->get()
                ->first();
            $dateTimeString = $check_time->date . ' ' . $check_time->time;
            $dateTime = Carbon::parse($dateTimeString);
            if ($now->gt($dateTime)) {
                // Use the query builder instance to update the record

                Book_ticket::where('id', $bookTicket->id)->update(['status' => 3]);
            }
        }
    }
}
