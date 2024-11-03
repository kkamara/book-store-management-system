<?php

namespace Database\Factories\V1;

use App\Models\V1\Book;
use App\Models\V1\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\OrderBook>
 */
class OrderBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $book = Book::where("approved", 1)
            ->inRandomOrder()
            ->first();
        if (null === $book) {
            $book = Book::factory()->create();
        }
        $order = Order::inRandomOrder()->first();
        if (null === $order) {
            $order = Order::factory()->create();
        }
        return [
            "order_id" => $order->id,
            "quantity" => mt_rand(1, 5),
            "isbn_13" => $book->isbn_13,
            "isbn_10" => $book->isbn_10,
            "slug" => $book->slug,
            "name" => $book->name,
            "description" => $book->description,
            "jpg_image_url" => $book->jpg_image_url,
            "cost" => $book->cost,
            "rating_average" => $book->rating_average,
            "binding" => $book->binding,
            "edition" => $book->edition,
            "author" => $book->author,
            "publisher" => $book->publisher,
            "published" => $book->published,
        ];
    }
}
