<?php

namespace Database\Factories\V1;

use App\Enums\V1\BookApproved;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\V1\User;
use App\Enums\V1\BookEdition;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isbn13 = null;
        $isbn10 = null;
        if (mt_rand(0, 1) === 1) {
            $isbn13 = $this->faker->isbn13();
            $isbn10 = $this->faker->isbn10();
            $edition = match(mt_rand(0, 13)) {
                0 => BookEdition::BIBLIOGRAPHICAL->value,
                1 => BookEdition::COLLECTORS->value,
                2 => BookEdition::PUBLISHER->value,
                3 => BookEdition::REVISED->value,
                4 => BookEdition::REVISED_UPDATED->value,
                5 => BookEdition::CO_EDITION->value,
                6 => BookEdition::E_DITION->value,
                7 => BookEdition::LIBRARY->value,
                8 => BookEdition::BOOK->value,
                9 => BookEdition::CHEAP->value,
                10 => BookEdition::COLONIAL->value,
                11 => BookEdition::CADET->value,
                12 => BookEdition::LARGE->value,
                13 => BookEdition::CRITICAL->value,
            };
        } else {
            $edition = BookEdition::E_DITION->value;
        }
        return [
            "isbn_13" => $isbn13,
            "isbn_10" => $isbn10,
            "user_id" => User::inRandomOrder()->first()->id,
            "name" => $this->faker->company(),
            "description" => $this->faker->paragraphs(mt_rand(3, 6), true),
            "cost" => $this->faker->numberBetween(),
            "rating_average" => null,
            "binding" => mt_rand(0, 1) === 1 ?
                "Paperback" :
                "Hardcover",
            "edition" => $edition,
            "author" => $this->faker->name(),
            "published" => Carbon::parse($this->faker->dateTimeInInterval())
                ->format("Y-m-d"),
            "publisher" => $this->faker->company(),
            "approved" => mt_rand(0, 1) === 1 ? 
                BookApproved::APPROVED->value :
                (mt_rand(0, 1) === 1 ?
                BookApproved::DISAPPROVED->value :
                BookApproved::NOT_JUDGED->value),
        ];
    }
}
