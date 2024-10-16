<?php

namespace Database\Factories\V1;

use App\Enums\V1\ReviewApproved;
use App\Models\V1\Book;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::inRandomOrder()->first()->id,
            "book_id" => Book::inRandomOrder()->first()->id,
            "rating" => (string) mt_rand(0, 5),
            "text" => mt_rand(0, 1) === 1 ?
                null :
                $this->faker->paragraphs(
                    mt_rand(1, 6),
                    true
                ),
            "approved" => mt_rand(0, 1) === 1 ? 
                ReviewApproved::APPROVED->value :
                (mt_rand(0, 1) === 1 ?
                ReviewApproved::DISAPPROVED->value :
                ReviewApproved::NOT_JUDGED->value),
        ];
    }
}
