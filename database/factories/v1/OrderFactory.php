<?php

namespace Database\Factories\V1;

use App\Enums\V1\OrderStatus;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\Order>
 */
class OrderFactory extends Factory
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
            "reference_number" => mt_rand(100000000, 999999999),
            "cost" => $this->faker->numberBetween(0, 2000),
            "delivery_cost" => 399,
            "status" => match(mt_rand(0, 3)) {
                0 => OrderStatus::PROCESSING,
                1 => OrderStatus::PROCESSED,
                2 => OrderStatus::DELIVERING,
                3 => OrderStatus::DELIVERED,
            },
        ];
    }
}
