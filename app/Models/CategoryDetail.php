<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class CategoryDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "category_details";
    protected $fillable = ['id', 'film_id', 'category_id'];
}
