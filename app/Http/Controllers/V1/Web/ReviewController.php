<?php

namespace App\Http\Controllers\V1\Web;

use App\Enums\V1\BookApproved;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReviewCollection;
use App\Models\V1\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    public function getReviewByBook(Request $request, string $slug) {
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
        return new ReviewCollection(
            $book->reviews()
                ->where("approved", 1)
                ->paginate(3)
        );
    }
}
