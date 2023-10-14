<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Film extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "films";
    protected $fillable = ['id', 'name', 'type', 'slug', 'status', 'trailer', 'time', 'image', 'release_date', 'description', 'status'];
}
