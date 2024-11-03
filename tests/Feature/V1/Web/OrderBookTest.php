<?php

namespace Tests\Feature\V1\Web;

use App\Models\V1\Order;
use App\Models\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class OrderBookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
     * A basic feature test OrdersController index route.
     */
    public function testOrderBooks(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $order = Order::where("user_id", $user->id)
            ->inRandomOrder()
            ->firstOrFail();
        $response = $this->getJson("/api/web/orders/".$order->reference_number."/products");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment(["id" => $order->orderBooks()->first()->id,])
            ->assertStatus(200);
    }

    /**
     * A basic feature test OrdersController index route not found by doesn't exist.
     */
    public function testOrderBooksNotFoundByDoesntExist(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $response = $this->getJson("/api/web/orders/doesntexist/products");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("message")
        )
            ->assertStatus(404);
    }

    /**
     * A basic feature test OrdersController index route not found by doesn't belong to user.
     */
    public function testOrderBooksNotFoundByDoesntBelongToUser(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $order = Order::where("user_id", "!=", $user->id)
            ->inRandomOrder()
            ->firstOrFail();
        $response = $this->getJson("/api/web/orders/".$order->reference_number."/products");
        $response->assertJson(value: fn (AssertableJson $json) =>
            $json->has("message")
        )
            ->assertStatus(404);
    }
}
