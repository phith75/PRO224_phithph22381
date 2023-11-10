<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Factories\DatabaseFactory;


class DatabaseSeeder extends Seeder
{

    public function seedAllTables()
    {

        $tables = [
            'categories',
            'films',
            'film_makers',
            'photos',
            'cinema_details',
            'movie_rooms',
            'time_details',
            'movie_chairs',
            'cinemas',
            'food_ticket_details',
            'food',
            'users',
            'book_tickets',
            'banners',
            'blogs',
            'contact_infos',
            'feedback',
            'times',
            'category_details'
        ];

        foreach ($tables as $table) {

            $model = ucfirst($table);

            switch ($table) {

                case 'category_details':

                    $data = [
                        [
                            'category_id' => 1,
                            'film_id' => 1
                        ],
                        [
                            'category_id' => 1,
                            'film_id' => 2
                        ],
                        [
                            'category_id' => 2,
                            'film_id' => 2
                        ]
                    ];


                    break;

                case 'users':

                    $data = [
                        [
                            'name' => 'John Doe',
                            'email' => 'john@email.com',
                            'phone' => '555-123-4567',
                            'role' => 1,
                            'image' => 'john.jpg',
                            'email_verified_at' => date('Y-m-d H:i:s'),
                            'password' => bcrypt('password123')
                        ],
                        [
                            'name' => 'Jane Doe',
                            'email' => 'jane@email.com',
                            'phone' => '555-456-7890',
                            'role' => 1,
                            'image' => 'jane.jpg',
                            'email_verified_at' => date('Y-m-d H:i:s'),
                            'password' => bcrypt('password456')
                        ]
                    ];

                    break;
                    break;

                case 'categories':
                    $data = [
                        ['name' => 'Action', 'slug' => 'action', 'status' => '1'],
                        ['name' => 'Comedy', 'slug' => 'comedy', 'status' => '1']
                    ];
                    break;

                case 'films':
                    $data = [
                        [
                            'name' => "Spider-Man: No Way Home",
                            'slug' => "spider-man-no-way-home",
                            'image' => "spider-man.jpg",
                            'trailer' => "https://youtube.com/spidermantrailer",
                            'time' => "150 minutes",
                            'release_date' => "2021-12-17",
                            'description' => "Peter Parker is unmasked...",
                            'status' => 1
                        ],
                        [
                            'name' => "The Batman",
                            'slug' => "the-batman",
                            'image' => "batman.jpg",
                            'trailer' => "https://youtube.com/batmantrailer",
                            'time' => "175 minutes",
                            'release_date' => "2022-03-04",
                            'description' => "Batman ventures into Gotham's underworld...",
                            'status' => 1
                        ]
                    ];
                    break;
                case 'film_makers':

                    $data = [

                        [
                            'type' => 1,
                            'name' => 'Tom Holland',
                            'image' => 'tom-holland.jpg',
                            'as' => 'Peter Parker/Spider-Man',
                            'film_id' => 1
                        ],

                        [
                            'type' => 1,
                            'name' => 'Robert Pattinson',
                            'image' => 'robert-pattinson.jpg',
                            'as' => 'Batman',
                            'film_id' => 2
                        ]

                    ];

                    break;

                case 'photos':

                    $data = [

                        [
                            'film_id' => 1,
                            'image' => 'spiderman1.jpg'
                        ],

                        [
                            'film_id' => 1,
                            'image' => 'spiderman2.jpg'
                        ],

                        [
                            'film_id' => 2,
                            'image' => 'batman1.jpg'
                        ]

                    ];

                    break;

                case 'cinema_details':

                    $data = [

                        ['cinema_id' => 1, 'film_id' => 1],

                        ['cinema_id' => 2, 'film_id' => 2]

                    ];

                    break;

                case 'movie_rooms':

                    $data = [

                        ['name' => 'Screen 1', 'id_cinema' => 1],

                        ['name' => 'Screen 2', 'id_cinema' => 1]

                    ];

                    break;
                case 'time_details':

                    $data = [

                        ['date' => '2022-06-15', 'time_id' => 1, 'film_id' => 1, 'room_id' => 1],

                        ['date' => '2022-06-16', 'time_id' => 2, 'film_id' => 1, 'room_id' => 1]

                    ];

                    break;

                case 'movie_chairs':

                    $data = [

                        ['name' => 'A1', 'price' => 10, 'id_time_detail'  => 1],

                        ['name' => 'A2', 'price' => 10, 'id_time_detail'  => 1],

                        ['name' => 'A3', 'price' => 10, 'id_time_detail' => 1],

                        ['name' => 'A4', 'price' => 10,  'id_time_detail'  => 1]

                    ];

                    break;

                case 'cinemas':

                    $data = [

                        ['name' => 'Cinema 1', 'address' => '123 Main St', 'status' => 1],

                        ['name' => 'Cinema 2', 'address' => '456 Elm Ave', 'status' => 1]

                    ];

                    break;

                case 'food_ticket_details':

                    $data = [
                        ['book_ticket_id' => 1, 'food_id' => 1],

                        ['book_ticket_id' => 2, 'food_id' => 2]
                    ];

                    break;

                case 'food':

                    $data = [

                        ['name' => 'Popcorn', 'image' => 'popcorn.jpg', 'price' => '5'],

                        ['name' => 'Soda', 'image' => 'soda.jpg', 'price' => '3']

                    ];

                    break;

                case 'book_tickets':

                    $data = [
                        ['user_id' => 1, 'id_time_detail' => 1, 'payment' => 1, 'amount' => 15, 'id_chair' => 1, 'time' => '10:00 AM'],

                        ['user_id' => 2, 'id_time_detail' => 2, 'payment' => 2, 'amount' => 10, 'id_chair' => 3, 'time' => '1:00 PM']
                    ];

                    break;

                case 'banners':

                    $data = [

                        ['title' => 'New Movie Out Now!', 'image' => 'banner1.jpg'],

                        ['title' => 'Coming Soon...', 'image' => 'banner2.jpg']

                    ];

                    break;

                case 'blogs':

                    $data = [

                        ['title' => 'Movie Review: The Dark Knight', 'slug' => 'movie-review-the-dark-knight', 'image' => 'review1.jpg', 'content' => 'Lorem ipsum...', 'status' => 1],

                        ['title' => 'Top 5 Movies of 2022', 'slug' => 'top-5-movies-of-2022', 'image' => 'review2.jpg', 'content' => 'Lorem ipsum...', 'status' => 1]

                    ];

                    break;

                case 'contact_infos':
                    $data = [
                        [
                            'user_id' => 1,
                            'email' => 'cinema1@movies.com',
                            'phone' => '555-123-4567',
                            'address' => '123 Movie St',
                        ],
                        [
                            'user_id' => 2,
                            'email' => 'support@movies.com',
                            'phone' => '555-456-7890',
                            'address' => 'Support Headquarters',
                        ]
                    ];
                    break;

                case 'feedback':

                    $data = [

                        ['user_id' => 1, 'content' => 'Great customer service', 'status' => 1],

                        ['user_id' => 2, 'content' => 'Staff was very helpful', 'status' => 1]

                    ];

                    break;

                case 'times':

                    $data = [

                        ['time' => '10:00 AM'],

                        ['time' => '1:00 PM'],

                        ['time' => '3:30 PM']

                    ];

                    break;


                default:
                    $data = [];
            }

            DB::table($table)->insert($data);
        }
    }
    public function run()
    {
        $this->seedAllTables();
    }
}
