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
        $response = $this->postJson(
            "/api/web/cart/update",
            [
                "cart" => [
                    [
                        "bookId" => $book->id,
                    ],
                ],
            ],
        );
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment([
                "name" => $book->name,
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
}
