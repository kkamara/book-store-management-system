<?php

namespace Database\Seeders;

use App\Models\V1\Book;
use App\Models\V1\BookCategory;
use App\Models\V1\Category;
use App\Models\V1\Order;
use App\Models\V1\OrderBook;
use App\Models\V1\Review;
use App\Models\V1\User;
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

        User::factory()->count(30)->create();
        User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@doe.com',
            'admin' => true,
        ]);
        Book::factory()->count(30)->create();
        Review::factory()->count(1000)->create();
        Category::factory()->count(30)->create();
        BookCategory::factory()->count(100)->create();
        Order::factory()->count(1000)->create();
        OrderBook::factory()->count(5000)->create();
    }
}
