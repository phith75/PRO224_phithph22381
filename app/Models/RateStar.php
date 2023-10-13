<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateStar extends Model
{
    use HasFactory;
    protected $table = "rate_stars";
    protected $fillable = ['id', 'comment', 'star'];
}
