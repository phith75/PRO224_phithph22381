<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;
    protected $table = "members";
    protected $fillable = ['id', 'id_card', 'card_class', 'activation_date', 'total_spending', 'accumulated_points', 'points_used', 'usable_points', 'id_user'];
}
