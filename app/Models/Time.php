<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Time extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "times";
    protected $fillable = ['id', 'time'];
}
