<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\FilmFactory;
use Database\Factories\CategoryFactory;
// etc
class DataFactory extends Factory
{
    protected $model = Film::class;

    public function definition()
    {
        // factory attributes 
    }

    public static function newFactory()
    {
        return Factory::new();
    }
}