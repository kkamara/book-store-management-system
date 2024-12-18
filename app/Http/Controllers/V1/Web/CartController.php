<?php

namespace App\Http\Controllers\V1\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartCollection;
use App\Models\V1\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CartCollection(
            Cart::where("user_id", auth()->user()->id)
                ->get()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "cart" => ["sometimes", "required", "array",],
                "cart.*.bookId" => ["sometimes", "required", "numeric", "exists:books,id",],
                "cart.*.quantity" => ["sometimes", "required", "numeric",],
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $user = auth()->user();
        $newCart = [];
        $bookIds = [];
        if (null !== $request->input("cart")) {
            foreach ($request->input("cart") as $key => $cart) {
                if (!isset($cart["bookId"])) {
                    $field = "cart.".$key.".bookId";
                    return response()->json([
                        $field => "The ".$field." field is missing.",
                    ], status: Response::HTTP_BAD_REQUEST);
                } else if (in_array($cart["bookId"], $bookIds)) {
                    $field = "cart.".$key.".bookId";
                    return response()->json([
                        $field => "The ".$field." field appears more than once in your payload.",
                    ], Response::HTTP_BAD_REQUEST);
                }
                if (!isset($cart["quantity"])) {
                    $field = "cart.".$key.".quantity";
                    return response()->json([
                        $field => "The ".$field." field is missing.",
                    ], Response::HTTP_BAD_REQUEST);
                }
                if (1 > $cart["quantity"]) {
                    continue;
                }
                $newCart[$key]["book_id"] = $cart["bookId"];
                $newCart[$key]["user_id"] = $user->id;
                $newCart[$key]["quantity"] = $cart["quantity"];
                array_push($bookIds, $cart["bookId"]);
            }
        }
        $user->carts()->delete();
        if (isset($newCart)) {
            $user->carts()->insert($newCart);
        }
        $cart = $user->carts()->get();
        return new CartCollection($cart);
    }

    public function addToCart(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                "cart" => ["required", "array",],
                "cart.bookId" => ["required", "numeric", "exists:books,id",],
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $user = auth()->user();
        $newCart = [];
        if (null !== $request->input("cart")) {
            $newCart["book_id"] = $request->input("cart")["bookId"];
            $newCart["user_id"] = $user->id;
        }
        $quantityAdded = false;
        foreach($user->carts()->get() as $key => $cart) {
            if ($cart->book_id === $newCart["book_id"]) {
                $cart->quantity += 1;
                $cart->save();
                $quantityAdded = true;
                break;
            }
        }
        if (isset($newCart) && false === $quantityAdded) {
            $newCart["quantity"] = 1;
            $user->carts()->insert($newCart);
        }
        $cart = $user->carts()->get();
        return new CartCollection($cart);
    }

    public function removeFromCart(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                "cart" => ["required", "array",],
                "cart.bookId" => ["required", "numeric", "exists:books,id",],
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $user = auth()->user();
        $newCart = [];
        if (null !== $request->input("cart")) {
            $newCart["book_id"] = $request->input("cart")["bookId"];
            $newCart["user_id"] = $user->id;
        }
        foreach($user->carts()->get() as $key => $cart) {
            if ($cart->book_id === $newCart["book_id"]) {
                if (($cart->quantity - 1) < 1) {
                    $cart->delete();
                    break;
                }
                $cart->quantity -= 1;
                $cart->save();
                break;
            }
        }
        $cart = $user->carts()->get();
        return new CartCollection($cart);
    }
}
