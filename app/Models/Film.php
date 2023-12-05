<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Film extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "films";
    protected $fillable = ['id', 'name', 'slug', 'status', 'limit_age', 'trailer', 'time', 'image', 'poster','release_date', 'end_date', 'description', 'status'];
}