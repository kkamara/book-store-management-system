<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BookCollection;
use App\Models\V1\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        return new BookCollection(
            Book::orderBy("id", "DESC")
                ->where("approved", 1)
                ->paginate(8)
                ->appends($request->query())
        );
    }
}
