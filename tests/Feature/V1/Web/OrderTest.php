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

class OrderTest extends TestCase
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
    public function testOrders(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $orders = Order::where("user_id", $user->id)
            ->orderBy("id", "DESC")
            ->paginate(8);
        $response = $this->getJson("/api/web/orders");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertJsonFragment(["referenceNumber" => $orders->first()->reference_number,])
            ->assertStatus(200);
    }

    /**
     * A basic feature test OrdersController index route with search.
     */
    public function testOrdersSearch(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $orders = Order::where("user_id", $user->id)
            ->orderBy("id", "DESC")
            ->paginate(8);
        $referenceNumber = $orders->first()->reference_number;
        $response = $this->getJson("/api/web/orders?query=".$referenceNumber);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertJsonFragment(["referenceNumber" => $referenceNumber,])
            ->assertStatus(200);
    }

    /**
     * A basic feature test OrdersController index route with search that doesnt exist.
     */
    public function testOrdersSearchDoesntExist(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $orders = Order::where("user_id", $user->id)
            ->orderBy("id", "DESC")
            ->paginate(8);
        $response = $this->getJson("/api/web/orders?query=doesntexist");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertJsonCount(0, "data")
            ->assertStatus(200);
    }

    /**
     * A basic feature test OrdersController get route.
     */
    public function testGetOrder(): void
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
        $response = $this->getJson("/api/web/orders/".$order->reference_number);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
        )
            ->assertJsonFragment(["referenceNumber" => $order->reference_number,])
            ->assertStatus(200);
    }

    /**
     * A basic feature test OrdersController get route not found by exists.
     */
    public function testGetOrderNotFoundByExists(): void
    {
        $this->seed();
        $email = "jane@doe.com";
        $user = User::where(compact("email"))->firstOrFail();
        Sanctum::actingAs(
            $user,
        );
        $response = $this->getJson("/api/web/orders/doesntexist");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("message")
        )
            ->assertStatus(404);
    }

    /**
     * A basic feature test OrdersController get route not found by doesn't belong to user.
     */
    public function testGetOrderNotFoundByDoesntBelongToUser(): void
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
        $response = $this->getJson("/api/web/orders/".$order->reference_number);
        $response->assertJson(value: fn (AssertableJson $json) =>
            $json->has("message")
        )
            ->assertStatus(404);
    }
}
