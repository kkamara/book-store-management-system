<?php

namespace Tests\Feature\V1\Web;

use App\Models\V1\Book;
use App\Models\V1\Cart;
use App\Models\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (!RefreshDatabaseState::$migrated) {
            $this->artisan(
                'migrate:fresh',
                array_merge(
                    $this->migrateFreshUsing(),
                    [
                        "--path" => "database/migrations/v1",
                    ],
                )
            );

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    /**
     * A basic feature test example for Cart index route.
     */
    public function testCart(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $cart = Cart::factory()
            ->create(["user_id" => $user->id]);
        $response = $this->getJson("/api/web/cart");
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment([
                "name" => $cart
                    ->book()
                    ->firstOrFail()
                    ->name,
            ]);
    }

    /**
     * A basic feature test example for Cart update route add items.
     */
    public function testCartUpdateAddItems(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $cart = Cart::factory()
            ->create(["user_id" => $user->id]);
        $book = Book::inRandomOrder()->firstOrFail();
        $quantity = mt_rand(1, 5);
        $response = $this->postJson(
            "/api/web/cart/update",
            [
                "cart" => [
                    [
                        "bookId" => $book->id,
                        "quantity" => $quantity,
                    ],
                ],
            ],
        );
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment([
                "name" => $book->name,
            ])
            ->assertJsonFragment(compact("quantity"));
    }

    /**
     * A basic feature test example for Cart update route duplicate add items.
     */
    public function testCartDuplicateUpdateAddItems(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $cart = Cart::factory()
            ->create(["user_id" => $user->id]);
        $book = Book::inRandomOrder()->firstOrFail();
        $quantity = mt_rand(1, 5);
        $response = $this->postJson(
            "/api/web/cart/update",
            [
                "cart" => [
                    [
                        "bookId" => $book->id,
                        "quantity" => $quantity,
                    ],
                    [
                        "bookId" => $book->id,
                        "quantity" => $quantity,
                    ],
                ],
            ],
        );
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("cart.1.bookId")->etc()
        )
            ->assertJsonFragment([
                "cart.1.bookId" => "The cart.1.bookId field appears more than once in your payload.",
            ]);
    }

    /**
     * A basic feature test example for Cart update route clear items.
     */
    public function testCartUpdateClearItems(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $cart = Cart::factory()
            ->create(["user_id" => $user->id]);
        $response = $this->postJson("/api/web/cart/update");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        );
    }

    /**
     * A basic feature test example for Cart addToCart route.
     */
    public function testCartAddToCart(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $book = Book::inRandomOrder()->firstOrFail();
        $cart = Cart::factory()
            ->create([
                "user_id" => $user->id,
                "book_id" => Book::where("id", "!=", $book->id)
                    ->firstOrFail()
                    ->id,
            ]);
        $response = $this->postJson(
            "/api/web/cart",
            [
                "cart" => [
                    "bookId" => $book->id,
                ],
            ],
        );
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment([
                "name" => $book->name,
                "quantity" => 1,
            ]);
    }

    /**
     * A basic feature test example for Cart addToCart route adds quantity.
     */
    public function testCartAddToCartAddsQuantity(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $book = Book::inRandomOrder()->firstOrFail();
        $cart = Cart::factory()
            ->create([
                "user_id" => $user->id,
                "book_id" => $book->id,
                "quantity" => 1,
            ]);
        $response = $this->postJson(
            "/api/web/cart",
            [
                "cart" => [
                    "bookId" => $book->id,
                ],
            ],
        );
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment([
                "name" => $book->name,
            ])
            ->assertJsonFragment([
                "quantity" => 2,
            ]);
    }

    /**
     * A basic feature test example for Cart removeFromCart route.
     */
    public function testCartRemoveFromCart(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $book = Book::inRandomOrder()->firstOrFail();
        $cart = Cart::factory()
            ->create([
                "user_id" => $user->id,
                "book_id" => $book->id,
                "quantity" => 1,
            ]);
        $response = $this->postJson(
            "/api/web/cart/remove",
            [
                "cart" => [
                    "bookId" => $book->id,
                ],
            ],
        );
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonMissing([
                "name" => $book->name,
            ]);
    }
}
