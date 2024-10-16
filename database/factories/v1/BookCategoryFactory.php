<?php

namespace Database\Factories\V1;

use App\Models\V1\Book;
use App\Models\V1\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\BookCategory>
 */
class BookCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "book_id" => Book::inRandomOrder()->first()->id,
            "category_id" => Category::inRandomOrder()->first()->id,
        ];
    }
}
