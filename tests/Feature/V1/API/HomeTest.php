<?php

namespace Tests\Feature\V1\API;

use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class HomeTest extends TestCase
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
     * A basic feature test HomeController home route.
     */
    public function testHome(): void
    {
        $this->seed();
        $response = $this->getJson("/api/web/");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta"])
                ->missing("message")
                ->etc()
        );
    }
}