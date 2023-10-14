<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class MovieRoom extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "movie_rooms";
    protected $fillable = ['id', 'name', 'quantity_chair'];
}
