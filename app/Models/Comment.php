<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comments"; // phải điền đúng tên bảng mà mình cần trỏ tới trong csdl
    protected $fillable = ['blogs_id', 'user_name', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blogs_id');
    }
}
