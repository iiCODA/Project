<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Get all items in the user's cart
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->with('items.product')->first();

        if (!$cart) {
            $statusCode = 404;
            return response()->json(
                ['message' => 'Cart is empty',
                 '$status_code'=>$statusCode], $statusCode);
        }

        return response()->json($cart);
    }

    // Add a product to the cart
    public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

    // Add or update the cart item
    $cartItem = CartItem::updateOrCreate(
        [
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
        ],
        [
            'quantity' => $request->quantity,
        ]
    );

    // Increment the number of items in the cart
    $cart->increment('number_of_items', 1);

    return response()->json(['message' => 'Product added to cart', 'cartItem' => $cartItem]);
}


    // Update the quantity of a product in the cart
    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::find($cartItemId);

        if (!$cartItem || $cartItem->cart->user_id !== $request->user()->id) {
            $statusCode = 403;
            return response()->json(['message' => 'Unauthorized','$status_code'=>$statusCode], $statusCode););
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart item updated', 'cartItem' => $cartItem]);
    }

    // Delete a product from the cart
    public function delete(Request $request, $cartItemId)
{
    $cartItem = CartItem::find($cartItemId);

    if (!$cartItem || $cartItem->cart->user_id !== $request->user()->id) {
        $statusCode = 403;
        return response()->json(['message' => 'Unauthorized', '$status_code'=>$statusCode], $statusCode);
    }

    // Decrement the number of items in the cart
    $cartItem->cart->decrement('number_of_items', 1);

    // Delete the cart item
    $cartItem->delete();

    return response()->json(['message' => 'Cart item removed']);
}

}
