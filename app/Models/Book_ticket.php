<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book_ticket extends Model
{
    use HasFactory;
    protected $table = "book_tickets"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'user_id', 'payment', 'amount', 'price', 'status'];
}
