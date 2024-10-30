<?php

namespace App\Http\Controllers\V1\Web;

use App\Enums\V1\BookApproved;
use App\Enums\V1\BookEdition;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BookResource;
use App\Http\Resources\V1\BookCollection;
use App\Models\V1\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    public function search(Request $request) {
        $books = new Book();
        $unsafeQuery = $request->input("query");
        if ($unsafeQuery) {
            $safeQuery = filter_var($unsafeQuery, FILTER_SANITIZE_STRING);
            $books = $books->where("isbn_13", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("isbn_10", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("name", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("cost", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("author", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("published", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("publisher", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("rating_average", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("binding", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("edition", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("created_at", "LIKE", "%".$safeQuery."%");
            $books = $books->orWhere("updated_at", "LIKE", "%".$safeQuery."%");
        }
        $unsafeOrderById = $request->input("orderById");
        if ($unsafeOrderById) {
            $books = match(strtolower($unsafeOrderById)) {
                "desc" => $books->orderBy("id", "DESC"),
                "asc" => $books->orderBy("id", "ASC"),
            };
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
        $unsafeSelectColonialEdition = $request->input("selectColonialEdition");
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
        } else if ($unsafeSelectColonialEdition) {
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
        return new BookCollection(
            $books->where("approved", 1)
                ->paginate(8)
                ->appends($request->query())
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
