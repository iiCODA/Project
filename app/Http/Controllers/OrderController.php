<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function createOrder(Request $request)
{
    $user = $request->user();

    // Validate the request data for one product
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::find($request->product_id);

    // Check if there is enough stock for the requested quantity
    if ($product->quantity < $request->quantity) {
        return response()->json([
            'message' => "Insufficient stock for product: {$product->name}",
        ], 400);
    }

    // Create the order
    $order = Order::create([
        'user_id' => $user->id,
        'total_price' => $product->price * $request->quantity,
    ]);

    // Create the order item
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => $request->quantity,
        'price' => $product->price,
    ]);

    // Decrease the product quantity in the inventory
    $product->decrement('quantity', $request->quantity);

    $user->notify(new \App\Notifications\OrderNotification('placed', $order));


    return response()->json([
        'message' => 'Order created successfully.',
        'order' => $order->load('orderItems.product'),
    ], 201);
}

public function submitCart(Request $request)
{
    // Get the authenticated user
    $user = $request->user();

    // Retrieve the user's cart
    $cart = $user->cart;

    // Check if the user has a cart and if the cart has no items
    if (!$cart || $cart->number_of_items === 0) {
        return response()->json(['message' => 'Your cart is empty'], 400);
    }

    // Retrieve the cart items using the correct relationship
    $cartItems = $cart->items;

    // Ensure cartItems is not null
    if (!$cartItems || $cartItems->isEmpty()) {
        return response()->json(['message' => 'Your cart is empty'], 400);
    }

    // Initialize total price to 0
    $totalPrice = 0;

    // Loop over each cart item and calculate the total price
    foreach ($cartItems as $cartItem) {
        // Get the price for the product
        $productPrice = $cartItem->product->price;

        // Calculate the total price for this cart item (price * quantity)
        $totalPrice += $productPrice * $cartItem->quantity;
    }

    // Proceed with the order creation
    $order = Order::create([
        'user_id' => $user->id,
        'total_price' => $totalPrice,
    ]);

    // Loop over the cart items to create order items and reduce product stock
    foreach ($cartItems as $cartItem) {
        // Check product stock
        if ($cartItem->product->quantity < $cartItem->quantity) {
            return response()->json([
                'message' => 'Not enough stock for product: ' . $cartItem->product->name,
            ], 400);
        }

        // Create an order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product->id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->product->price,
        ]);

        // Reduce product quantity
        $cartItem->product->update([
            'quantity' => $cartItem->product->quantity - $cartItem->quantity,
        ]);
    }

    // Clear the cart items and reset the number of items to 0
    $cart->items()->delete();  // Correct method to delete cart items
    $cart->update(['number_of_items' => 0]);

    $user->notify(new \App\Notifications\OrderNotification('placed', $order));


    return response()->json([
        'message' => 'Order created successfully',
        'order' => $order,
        'order_items' => $order->orderItems,
    ]);
}

public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Retrieve all orders for the user, including their order items
        $orders = Order::with('orderItems.product') // Include order items and product details
                        ->where('user_id', $user->id) // Filter orders by user
                        ->get();

        // If the user has no orders
        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return response()->json($orders);
    }





    public function deleteOrder(Request $request, $orderId)
    {
        $user = $request->user();
    
        // Find the order by ID and ensure it belongs to the authenticated user
        $order = Order::where('user_id', $user->id)->findOrFail($orderId);
    
        // Loop through the order items to restore the product quantities
        foreach ($order->orderItems as $orderItem) {
            $product = $orderItem->product;
    
            // Restore the product quantity
            $product->increment('quantity', $orderItem->quantity);
        }
    
        // Delete the associated order items
        $order->orderItems()->delete();
    
        // Delete the order itself
        $order->delete();
    
        $user->notify(new OrderNotification('deleted', $order));

        return response()->json(['message' => 'Order deleted successfully, and product stock restored.']);
    }
    

public function updateOrder(Request $request, $orderId)
{
    $user = $request->user();

    // Validate the incoming data
    $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:0',
    ]);

    // Find the order and ensure it belongs to the authenticated user
    $order = Order::where('user_id', $user->id)->findOrFail($orderId);

    $totalPrice = 0;

    foreach ($request->items as $item) {
        $product = Product::findOrFail($item['product_id']);

        // Find the corresponding order item
        $orderItem = $order->orderItems()->where('product_id', $item['product_id'])->first();

        if ($item['quantity'] == 0) {
            // Remove the item from the order if quantity is set to 0
            if ($orderItem) {
                $product->increment('quantity', $orderItem->quantity); // Restore product stock
                $orderItem->delete();
            }
        } elseif ($orderItem) {
            // Update the quantity of the existing order item
            $stockAdjustment = $item['quantity'] - $orderItem->quantity;

            if ($product->quantity < $stockAdjustment) {
                return response()->json(['message' => "Not enough stock for product: {$product->name}"], 400);
            }

            $orderItem->update(['quantity' => $item['quantity']]);
            $product->decrement('quantity', $stockAdjustment);

            $totalPrice += $orderItem->price * $item['quantity'];
        } else {
            // Add a new product to the order
            if ($product->quantity < $item['quantity']) {
                return response()->json(['message' => "Not enough stock for product: {$product->name}"], 400);
            }

            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            $product->decrement('quantity', $item['quantity']);
            $totalPrice += $product->price * $item['quantity'];
        }
    }

    // Update the total price of the order
    $order->update(['total_price' => $totalPrice]);

    $user->notify(new OrderNotification('updated', $order));

    return response()->json(['message' => 'Order updated successfully', 'order' => $order->load('orderItems.product')]);
}


}
