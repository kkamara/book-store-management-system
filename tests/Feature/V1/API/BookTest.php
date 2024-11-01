<?php

namespace Tests\Feature\V1\API;

use App\Enums\V1\BookEdition;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Contracts\Console\Kernel;
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
     * A basic feature test BookController index route.
     */
    public function testSearchBooks(): void
    {
        $this->seed();
        $response = $this->getJson(
            "/api/web/books/search",
        );
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertStatus(200);
    }

    /**
     * A basic feature test BookController index route by query.
     */
    public function testSearchBooksByQuery(): void
    {
        $this->seed();
        $book = Book::where("approved", 1)
            ->inRandomOrder()
            ->firstOrFail();
        $response = $this->getJson(
            "/api/web/books/search?query=".$book->name,
        );
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertJsonFragment(["name" => $book->name,])
            ->assertStatus(200);
    }

    /**
     * A basic feature test BookController index route by selected edition.
     */
    public function testSearchBooksBySelectedEdition(): void
    {
        $this->seed();
        $book = Book::where("approved", 1)
            ->inRandomOrder()
            ->firstOrFail();
        switch($book->edition->value) {
            case BookEdition::BIBLIOGRAPHICAL->value:
                $editionSearchFilterKey = "selectBibliographicalEdition";
                $edition = BookEdition::BIBLIOGRAPHICAL->value;
                break;
            case BookEdition::COLLECTORS->value:
                $editionSearchFilterKey = "selectCollectorsEdition";
                $edition = BookEdition::COLLECTORS->value;
                break;
            case BookEdition::PUBLISHER->value:
                $editionSearchFilterKey = "selectPublisherEdition";
                $edition = BookEdition::PUBLISHER->value;
                break;
            case BookEdition::REVISED->value:
                $editionSearchFilterKey = "selectRevisedEdition";
                $edition = BookEdition::REVISED->value;
                break;
            case BookEdition::REVISED_UPDATED->value:
                $editionSearchFilterKey = "selectRevisedUpdatedEdition";
                $edition = BookEdition::REVISED_UPDATED->value;
                break;
            case BookEdition::CO_EDITION->value:
                $editionSearchFilterKey = "selectCoEditionEdition";
                $edition = BookEdition::CO_EDITION->value;
                break;
            case BookEdition::E_DITION->value:
                $editionSearchFilterKey = "selectEditionEdition";
                $edition = BookEdition::E_DITION->value;
                break;
            case BookEdition::LIBRARY->value:
                $editionSearchFilterKey = "selectLibraryEdition";
                $edition = BookEdition::LIBRARY->value;
                break;
            case BookEdition::BOOK->value:
                $editionSearchFilterKey = "selectBookEdition";
                $edition = BookEdition::BOOK->value;
                break;
            case BookEdition::CHEAP->value:
                $editionSearchFilterKey = "selectCheapEdition";
                $edition = BookEdition::CHEAP->value;
                break;
            case BookEdition::COLONIAL->value:
                $editionSearchFilterKey = "selectColonialEdition";
                $edition = BookEdition::COLONIAL->value;
                break;
            case BookEdition::CADET->value:
                $editionSearchFilterKey = "selectCadetEdition";
                $edition = BookEdition::CADET->value;
                break;
            case BookEdition::LARGE->value:
                $editionSearchFilterKey = "selectLargeEdition";
                $edition = BookEdition::LARGE->value;
                break;
            case BookEdition::CRITICAL->value:
                $editionSearchFilterKey = "selectCriticalEdition";
                $edition = BookEdition::CRITICAL->value;
                break;
        }
        $response = $this->getJson(
            "/api/web/books/search?".$editionSearchFilterKey."=1",
        );
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertJsonFragment(compact("edition"))
            ->assertStatus(200);
    }

    /**
     * A basic feature test BookController index route by selected category.
     */
    public function testSearchBooksBySelectedCategory(): void
    {
        $this->seed();
        $book = Book::where("approved", 1)
            ->inRandomOrder()
            ->whereHas("categories")
            ->firstOrFail();
        $categoryName = $book->categories()->firstOrFail()->name;
        $response = $this->getJson(
            "/api/web/books/search?category=".$categoryName,
        );
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(["data", "links", "meta",])
        )
            ->assertJsonFragment(["name" => $categoryName,])
            ->assertStatus(200);
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
