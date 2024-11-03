<?php

namespace Database\Factories\V1;

use App\Models\V1\Book;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "book_id" => Book::inRandomOrder()->firstOrFail()->id,
            "user_id" => User::inRandomOrder()->firstOrFail()->id,
            "quantity" => mt_rand(1, 5),
        ];
    }
}
