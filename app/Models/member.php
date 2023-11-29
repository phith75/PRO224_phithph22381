<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;
    protected $table = "members";
    protected $fillable = ['id', 'id_card', 'card_class', 'activation_date', 'total_spending', 'accumulated_points', 'points_used', 'usable_points', 'id_user'];
    //cập nhật ngay
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($member) {
            // Kiểm tra nếu total_spending đạt giá trị cần thiết, cập nhật card_class
            if ($member->total_spending >= 3000000) {
                $member->card_class = 2; // Cập nhật giá trị của card_class
            }
            $ratio = 1000;
            $member->accumulated_points = $member->total_spending / $ratio;
        });
    }

    //cập nhật từ ngày tiếp theo cái trên để test xem ổn chưa 
    //nếu ổn rồi thì lấy cái dưới bỏ cái trên
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::updating(function ($member) {
    //         // Kiểm tra ngày tạo mới cập nhật
    //         $today = Carbon::today();
    //         $updatedAt = Carbon::parse($member->updated_at);

    //         // Chỉ cập nhật nếu ngày cập nhật là sau ngày hiện tại
    //         if ($updatedAt->gt($today)) {
    //             // Kiểm tra nếu total_spending đạt giá trị cần thiết, cập nhật card_class
    //             if ($member->total_spending >= 3000000) {
    //                 $member->card_class = 2; // Cập nhật giá trị của card_class
    //             }
    //         }
            // $ratio = 1000;
            // $member->accumulated_points = $member->total_spending / $ratio;
    //     });
    // }
}
