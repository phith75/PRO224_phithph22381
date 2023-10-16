<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class RateStar extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "rate_stars";
    protected $fillable = ['id', 'comment', 'star'];
}
