<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Book_ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "book_tickets"; // phải điền đúng tên bảng mà mình cần trỏ tới trong 
    protected $fillable = ['id', 'id_time_detail', 'user_id', 'status','payment', 'amount','time' ,'id_code','id_staff_check'];
    protected static function boot()
    {
        parent::boot();
        static::created(function ($bookTicket) {
            $bookTicket->updateMemberTotalSpending();
        });
    }

    public function updateMemberTotalSpending()
    {
        $member = Member::where('id_user', $this->user_id)->first();
        if ($member) {
            $usable_speding = $member->usable_points + $this->amount / 100;
            $newTotalSpending = $member->total_spending + $this->amount;
            $member->update(['total_spending' => $newTotalSpending, 'usable_points' => $usable_speding]);
        }
    }
}
