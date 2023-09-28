<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book_ticket_detail extends Model
{
    use HasFactory;
    protected $table = "book_ticket_details"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'book_ticket_id', 'time_id', 'food_id', 'chair', 'quantity', 'price'];
}
