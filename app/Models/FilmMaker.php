<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmMaker extends Model
{
    use HasFactory;
    protected $table = "film_makers";
    protected $fillable = ['id', 'type', 'name', 'image', 'as', 'film_id'];
}
