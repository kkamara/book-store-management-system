<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderCollection;
use App\Models\V1\Order;
use Illuminate\Http\Request;

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
            $orders->where("reference_number", "LIKE", "%".$safeQuery."%");
            $orders->orWhere("cost", "LIKE", "%".$safeQuery."%");
            $orders->orWhere("delivery_cost", "LIKE", "%".$safeQuery."%");
            $orders->orWhere("status", "LIKE", "%".$safeQuery."%");
            $orders->orWhere("created_at", "LIKE", "%".$safeQuery."%");
            $orders->orWhere("updated_at", "LIKE", "%".$safeQuery."%");
        }
        return new OrderCollection(
            $orders->paginate(8)
                ->appends($request->query())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
