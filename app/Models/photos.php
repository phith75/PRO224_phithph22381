<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class photos extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "photos";
    protected $fillable = ['id', 'film_id', 'image'];
}
