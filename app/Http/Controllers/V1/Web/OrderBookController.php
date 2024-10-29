<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderBookCollection;
use App\Models\V1\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $referenceNumber)
    {
        $order = Order::where("reference_number", $referenceNumber)->first();
        if (null === $order) {
            return response()->json([
                "message" => "Resource not found.",
            ], Response::HTTP_NOT_FOUND);
        }
        if (auth()->user()->id !== $order->user_id) {
            return response()->json([
                "message" => "Resource not found.",
            ], Response::HTTP_NOT_FOUND);
        }
        return new OrderBookCollection($order->orderBooks);
    }
}
