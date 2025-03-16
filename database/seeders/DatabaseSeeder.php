<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Database\Factories\BookFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        Book::factory(33)->create()->each(
            function($book){
                $num = random_int(5,30);
                Review::factory($num)->for($book)->good()->create();
            }

        );

        Book::factory(34)->create()->each(
            function($book){
                $num = random_int(5,30);
                Review::factory($num)->for($book)->average()->create();
            }

        );

        Book::factory(33)->create()->each(
            function($book){
                $num = random_int(5,30);
                Review::factory($num)->for($book)->bad()->create();
            }

        );
    }
}
