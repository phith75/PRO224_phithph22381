<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieRoom extends Model
{
    use HasFactory;
    protected $table = "movie_rooms";
    protected $fillable = ['id', 'name', 'quantity_chair'];
}
