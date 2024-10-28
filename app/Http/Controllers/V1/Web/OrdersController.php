<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderCollection;
use App\Models\V1\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::where("user_id", auth()->user()->id)
            ->orderBy("id", "DESC");
        if ($request->input("query")) {
            $query = filter_var($request->input("query"), FILTER_SANITIZE_STRING);
            $orders->where("reference_number", "LIKE", "%".$query."%");
            $orders->orWhere("cost", "LIKE", "%".$query."%");
            $orders->orWhere("delivery_cost", "LIKE", "%".$query."%");
            $orders->orWhere("status", "LIKE", "%".$query."%");
            $orders->orWhere("created_at", "LIKE", "%".$query."%");
            $orders->orWhere("updated_at", "LIKE", "%".$query."%");
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
