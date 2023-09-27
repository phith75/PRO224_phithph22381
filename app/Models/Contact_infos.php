<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_infos extends Model
{
    use HasFactory;
    protected $table = "contact_infos"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['id', 'user_id', 'email', 'address', 'phone'];
}
