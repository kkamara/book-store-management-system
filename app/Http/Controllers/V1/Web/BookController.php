<?php

namespace App\Http\Controllers\V1\Web;

use App\Enums\V1\BookApproved;
use App\Enums\V1\BookEdition;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BookResource;
use App\Http\Resources\V1\BookCollection;
use App\Http\Resources\V1\CategoryCollection;
use App\Models\V1\Book;
use App\Models\V1\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    public function search(Request $request) {
        $books = (new Book())->select([
            "books.id", "books.user_id", "books.isbn_13", "books.isbn_10", 
            "books.slug", "books.name", "books.description", "books.jpg_image_url",
            "books.cost", "books.rating_average", "books.binding", "books.edition",
            "books.author", "books.published", "books.publisher", "books.approved",
        ])->distinct();
        $unsafeQuery = $request->input("query");
        if ($unsafeQuery) {
            $safeQuery = filter_var($unsafeQuery, FILTER_SANITIZE_STRING);
            $books = $books->where("isbn_13", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("isbn_10", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("books.name", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("cost", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("author", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("published", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("publisher", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("rating_average", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("binding", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("edition", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("books.created_at", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("books.updated_at", "LIKE", "%".$safeQuery."%");
        }
        $unsafeOrderById = $request->input("orderById");
        if ($unsafeOrderById) {
            $books = match(strtolower($unsafeOrderById)) {
                "desc" => $books->orderBy("books.id", "DESC"),
                "asc" => $books->orderBy("books.id", "ASC"),
            };
        } else {
            $books = $books->orderBy("books.id", "DESC");
        }
        
        $unsafeSelectBibliographicalEdition = $request->input("selectBibliographicalEdition");
        $unsafeSelectCollectorsEdition = $request->input("selectCollectorsEdition");
        $unsafeSelectPublisherEdition = $request->input("selectPublisherEdition");
        $unsafeSelectRevisedEdition = $request->input("selectRevisedEdition");
        $unsafeSelectRevisedUpdatedEdition = $request->input("selectRevisedUpdatedEdition");
        $unsafeSelectCoEditionEdition = $request->input("selectCoEditionEdition");
        $unsafeSelectEditionEdition = $request->input("selectEditionEdition");
        $unsafeSelectLibraryEdition = $request->input("selectLibraryEdition");
        $unsafeSelectBookEdition = $request->input("selectBookEdition");
        $unsafeSelectCheapEdition = $request->input("selectCheapEdition");
        $unsafeSelectColonialEdition = $request->input("selectColonialEdition");
        $unsafeSelectCadetEdition = $request->input("selectCadetEdition");
        $unsafeSelectLargeEdition = $request->input("selectLargeEdition");
        $unsafeSelectCriticalEdition = $request->input("selectCriticalEdition");
        if ($unsafeSelectBibliographicalEdition) {
            $books = $books->where(
                "edition",
                BookEdition::BIBLIOGRAPHICAL->value
            );
        } else if ($unsafeSelectCollectorsEdition) {
            $books = $books->where(
                "edition",
                BookEdition::COLLECTORS->value
            );
        } else if ($unsafeSelectPublisherEdition) {
            $books = $books->where(
                "edition",
                BookEdition::PUBLISHER->value
            );
        } else if ($unsafeSelectRevisedEdition) {
            $books = $books->where(
                "edition",
                BookEdition::REVISED->value
            );
        } else if ($unsafeSelectRevisedUpdatedEdition) {
            $books = $books->where(
                "edition",
                BookEdition::REVISED_UPDATED->value
            );
        } else if ($unsafeSelectCoEditionEdition) {
            $books = $books->where(
                "edition",
                BookEdition::CO_EDITION->value
            );
        } else if ($unsafeSelectEditionEdition) {
            $books = $books->where(
                "edition",
                BookEdition::E_DITION->value
            );
        } else if ($unsafeSelectLibraryEdition) {
            $books = $books->where(
                "edition",
                BookEdition::LIBRARY->value
            );
        } else if ($unsafeSelectBookEdition) {
            $books = $books->where(
                "edition",
                BookEdition::BOOK->value
            );
        } else if ($unsafeSelectCheapEdition) {
            $books = $books->where(
                "edition",
                BookEdition::CHEAP->value
            );
        } else if ($unsafeSelectColonialEdition) {
            $books = $books->where(
                "edition",
                BookEdition::COLONIAL->value
            );
        } else if ($unsafeSelectCadetEdition) {
            $books = $books->where(
                "edition",
                BookEdition::CADET->value
            );
        } else if ($unsafeSelectLargeEdition) {
            $books = $books->where(
                "edition",
                BookEdition::LARGE->value
            );
        } else if ($unsafeSelectCriticalEdition) {
            $books = $books->where(
                "edition",
                BookEdition::CRITICAL->value
            );
        }

        $unsafeCategory = $request->input("category");
        if ($unsafeCategory) {
            $safeCategory = filter_var(
                $request->input("category"),
                FILTER_SANITIZE_STRING
            );
            $books = $books->leftJoin("book_categories", "book_categories.book_id", "=", "books.id")
                ->leftJoin("categories", "book_categories.category_id", "=", "categories.id")
                ->where(
                    "categories.name",
                    "LIKE",
                    "%".$safeCategory."%",
                );
        }
        return new BookCollection(
            $books->where("approved", 1)
                ->paginate(8)
                ->appends($request->query())
        );
    }

    public function editions() {
        return [
            "data" => [
                [
                    "name" => BookEdition::BIBLIOGRAPHICAL->value,
                    "filterKey" => "selectBibliographicalEdition",
                ],
                [
                    "name" => BookEdition::COLLECTORS->value,
                    "filterKey" => "selectCollectorsEdition",],
                [
                    "name" => BookEdition::PUBLISHER->value,
                    "filterKey" => "selectPublisherEdition",],
                [
                    "name" => BookEdition::REVISED->value,
                    "filterKey" => "selectRevisedEdition",],
                [
                    "name" => BookEdition::REVISED_UPDATED->value,
                    "filterKey" => "selectRevisedUpdatedEdition",],
                [
                    "name" => BookEdition::CO_EDITION->value,
                    "filterKey" => "selectCoEditionEdition",
                ],
                [
                    "name" => BookEdition::E_DITION->value,
                    "filterKey" => "selectEditionEdition",
                ],
                [
                    "name" => BookEdition::LIBRARY->value,
                    "filterKey" => "selectLibraryEdition",
                ],
                [
                    "name" => BookEdition::BOOK->value,
                    "filterKey" => "selectBookEdition",
                ],
                [
                    "name" => BookEdition::CHEAP->value,
                    "filterKey" => "selectCheapEdition",
                ],
                [
                    "name" => BookEdition::COLONIAL->value,
                    "filterKey" => "selectColonialEdition",
                ],
                [
                    "name" => BookEdition::CADET->value,
                    "filterKey" => "selectCadetEdition",
                ],
                [
                    "name" => BookEdition::LARGE->value,
                    "filterKey" => "selectLargeEdition",
                ],
                [
                    "name" => BookEdition::CRITICAL->value,
                    "filterKey" => "selectCriticalEdition",
                ],
            ],
        ];
    }

    public function categories() {
        return new CategoryCollection(
            Category::distinct()
                ->orderBy("name", "ASC")
                ->get()
        );
    }

    public function get(Request $request, string $slug) {
        $book = Book::where("slug", $slug)->first();
        if (null === $book) {
            return response()->json([
                "message" => "Resource not found.",
            ], Response::HTTP_NOT_FOUND);
        }
        if ($book->approved !== BookApproved::APPROVED) {
            return response()->json([
                "message" => "Resource not found.",
            ], Response::HTTP_NOT_FOUND);
        }
        return new BookResource($book);
    }
}
