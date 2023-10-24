<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chairs extends Model
{
    use HasFactory;
    protected $table = "movie_chairs"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'name', 'price', 'id_time_detail', 'type'];
}
