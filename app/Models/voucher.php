<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "vouchers";
    protected $fillable = ['code', 'start_time', 'end_time', 'usage_limit', 'price_voucher', 'limit', 'remaining_limit', 'percent','description', 'minimum_amount'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voucher) {
            $voucher->remaining_limit = $voucher->usage_limit;
        });
    }

    public function decreaseRemainingLimit()
    {
        if ($this->remaining_limit > 0) {
            $this->remaining_limit--;
            $this->save();
        }
    }

    public function updateRemainingLimit()
    {
        if ($this->remaining_limit > 0) {
            $this->remaining_limit--;
            $this->save();
        }
    }
}