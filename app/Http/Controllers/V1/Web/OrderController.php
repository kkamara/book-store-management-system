<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderCollection;
use App\Http\Resources\V1\OrderResource;
use App\Models\V1\Book;
use App\Models\V1\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::where("user_id", auth()->user()->id)
            ->orderBy("id", "DESC");
        $unsafeQuery = $request->input("query");
        if ($unsafeQuery) {
            $safeQuery = filter_var($unsafeQuery, FILTER_SANITIZE_STRING);
            $orders = $orders->where("reference_number", "LIKE", "%".$safeQuery."%");
            $orders = $orders->orWhere("cost", "LIKE", "%".$safeQuery."%");
            $orders = $orders->orWhere("delivery_cost", "LIKE", "%".$safeQuery."%");
            $orders = $orders->orWhere("status", "LIKE", "%".$safeQuery."%");
            $orders = $orders->orWhere("created_at", "LIKE", "%".$safeQuery."%");
            $orders = $orders->orWhere("updated_at", "LIKE", "%".$safeQuery."%");
        }
        return new OrderCollection(
            $orders->paginate(8)
                ->appends($request->query())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $referenceNumber)
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
        return new OrderResource($order);
    }
}
