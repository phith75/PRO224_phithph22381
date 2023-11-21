<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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
        $currentTime = Carbon::now();
        $seat_reservation = Cache::get('seat_reservation', []);

        foreach ($seat_reservation as $id_time_detail => &$users) {
            foreach ($users as $id_user => &$data) {
                foreach ($data['time'] as $seat => $timestamp) {
                    // Kiểm tra số giây giữa thời điểm hiện tại và thời điểm ghế được đặt
                    $secondsDifference = $currentTime->diffInSeconds($timestamp);

                    // Nếu số giây vượt quá 1 phút, xóa thông tin ghế hết hạn
                    if ($secondsDifference >= 60) {
                        unset($data['seat'][$seat]);
                        unset($data['time'][$seat]);
                    }
                }
            }
        }

        // Cập nhật dữ liệu vào Cache
        Cache::put('seat_reservation', $seat_reservation);
    }
}
