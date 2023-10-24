<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class FilmMaker extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "film_makers";
    protected $fillable = ['id', 'type', 'name', 'image', 'as', 'film_id'];
}
