<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $table = "films";
    protected $fillable = ['id', 'name', 'type', 'slug', 'status', 'trailer', 'time', 'image', 'release_date', 'description', 'status'];
}
