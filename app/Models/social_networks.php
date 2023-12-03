<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete


class social_networks extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "social_networks";
    protected $fillable = ['id', 'user_id', 'type', 'social_id'];
}
