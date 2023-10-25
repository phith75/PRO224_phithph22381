<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Chairs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "movie_chairs"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'name', 'price', 'id_time_detail', 'type'];
}
