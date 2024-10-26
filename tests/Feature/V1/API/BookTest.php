<?php

namespace Tests\Feature\V1\API;

use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Contracts\Console\Kernel;
use App\Http\Resources\V1\BookCollection;
use App\Models\V1\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class BookTest extends TestCase
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
     * A basic feature test BookController get route.
     */
    public function testBook(): void
    {
        $this->seed();
        $book = Book::where("approved", 1)->firstOrFail();
        $response = $this->getJson("/api/web/books/".$book->slug);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
                ->missing("message")
        )
            ->assertJsonPath("data.id", $book->id)
            ->assertStatus(200);
    }

    /**
     * A basic feature test BookController get route where book should not be found.
     */
    public function testBookNotFoundByExists(): void
    {
        $this->seed();
        $response = $this->getJson("/api/web/books/doesnt-exist");
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("message")
                ->missing("data")
        )
            ->assertJson(["message" => "Resource not found."])
            ->assertStatus(404);
    }

    /**
     * A basic feature test BookController get route where book should not be found.
     */
    public function testBookNotFoundByApproved(): void
    {
        $this->seed();
        $book = Book::where("approved", "!=", 1)->firstOrFail();
        $response = $this->getJson("/api/web/books/".$book->slug);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("message")
                ->missing("data")
        )
            ->assertJson(["message" => "Resource not found."])
            ->assertStatus(404);
    }
}
