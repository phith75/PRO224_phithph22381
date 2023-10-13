<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SebastianBergmann\FileIterator\Factory;
use Database\Seeders\DatabaseFactory;


class seederFactories extends Seeder
{
    /**
     * 
     * Run the database seeds.
     */
    public function run(): void
    {   
        $factory = DatabaseFactory::factory(); 
        
        $factory->define(Category::class, function() {
            return [
              'id' => $faker->unique()->numberBetween(1,100), 
              'name' => $faker->word,
              'slug' => $faker->slug, 
              'status' => $faker->randomDigit
            ];
          });
          
          // Table 2 - Category_details  
          $factory->define(CategoryDetail::class, function() {
            return [
              'category_id' => Category::factory(),
              'film_id' => Film::factory()
            ];
          });
          
          // Table 3 - Times
          $factory->define(Time::class, function() {
            return [
              'id' => $faker->unique()->numberBetween(1,100),
              'time' => $faker->time
            ];
          });
          
          // Table 4 - Film_makers
          $factory->define(FilmMaker::class, function() {
            return [
              'id' => $faker->unique()->numberBetween(1,100),
              'type' => $faker->randomDigit,
              'name' => $faker->name,
              'image' => $faker->imageUrl,
              'as' => $faker->sentence,
              'film_id' => Film::factory() 
            ];
          });
    }
}