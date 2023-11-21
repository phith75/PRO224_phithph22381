<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Book_ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "book_tickets"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'user_id', 'payment', 'amount', 'id_chair', 'time', 'id_code'];
}
