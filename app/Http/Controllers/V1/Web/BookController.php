<?php

namespace App\Http\Controllers\V1\Web;

use App\Enums\V1\BookApproved;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BookResource;
use App\Models\V1\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    public function get(Request $request, Book $book) {
        if ($book->approved !== BookApproved::APPROVED) {
            return response()->json([
                "message" => "Resource not found.",
            ], Response::HTTP_NOT_FOUND);
        }
        return new BookResource($book);
    }
}
