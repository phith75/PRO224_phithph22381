<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Food_ticket_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "food_ticket_details"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'book_ticket_id', 'food_id', 'quantity'];
}