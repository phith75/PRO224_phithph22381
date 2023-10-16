<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $table = "films";
    protected $fillable = ['id', 'name', 'slug','image', 'trailer', 'time', 'release_date', 'description', 'status'];
}
