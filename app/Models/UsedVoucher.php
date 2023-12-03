<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedVoucher extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'voucher_code', 'used_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với bảng Vouchers
    public function voucher()
    {
        return $this->belongsTo(voucher::class, 'voucher_code', 'code');
    }
}
