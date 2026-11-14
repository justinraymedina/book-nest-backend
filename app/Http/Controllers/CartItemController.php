<?php

namespace App\Http\Controllers;
use App\Models\CartItem;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index() { return CartItemResource::collection(CartItem::with('product')->latest()->get()); }
    public function store(Request $request) {
        $validated = $request->validate([
            'user_id'=>'required|exists:users,id',
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1'
        ]);
        $item = CartItem::create($validated);
        return new CartItemResource($item->load('product'));
    }
    public function show(CartItem $cart) { return new CartItemResource($cart->load('product')); }
    public function update(Request $request, CartItem $cart) {
        $validated = $request->validate(['quantity'=>'required|integer|min:1']);
        $cart->update($validated);
        return new CartItemResource($cart->load('product'));
    }
    public function destroy(CartItem $cart) { $cart->delete(); return response()->json(['message'=>'Cart item deleted']); }
}
