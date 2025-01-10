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

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);sssss

        $product = Product::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return response()->json([
                'message' => "Insufficient stock for product: {$product->name}",
            ], 400);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price * $request->quantity,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        $product->decrement('quantity', $request->quantity);

        $user->notify(new OrderNotification('placed', $order));

        return response()->json([
            'message' => 'Order created successfully.',
            'order' => $order->load('orderItems.product'),
        ], 201);
    }

    public function submitCart(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;

        if (!$cart || $cart->number_of_items === 0) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

        $cartItems = $cart->items;

        if (!$cartItems || $cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

        $totalPrice = 0;

        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->quantity < $cartItem->quantity) {
                return response()->json([
                    'message' => 'Not enough stock for product: ' . $cartItem->product->name,
                ], 400);
            }

            $totalPrice += $cartItem->product->price * $cartItem->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice,
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);

            $cartItem->product->decrement('quantity', $cartItem->quantity);
        }

        $cart->items()->delete();
        $cart->update(['number_of_items' => 0]);

        $user->notify(new OrderNotification('placed', $order));

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
            'order_items' => $order->orderItems,
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::with('orderItems.product')->where('user_id', $user->id)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return response()->json($orders);
    }

    public function deleteOrder(Request $request, $orderId)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        foreach ($order->orderItems as $orderItem) {
            $orderItem->product->increment('quantity', $orderItem->quantity);
        }

        $order->orderItems()->delete();
        $order->delete();

        $user->notify(new OrderNotification('deleted', $order));

        return response()->json(['message' => 'Order deleted successfully, and product stock restored.']);
    }

    public function updateOrder(Request $request, $orderId)
    {
        $user = $request->user();

        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);
        $totalPrice = 0;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $orderItem = $order->orderItems()->where('product_id', $item['product_id'])->first();

            if ($item['quantity'] == 0) {
                if ($orderItem) {
                    $product->increment('quantity', $orderItem->quantity);
                    $orderItem->delete();
                }
            } elseif ($orderItem) {
                $stockAdjustment = $item['quantity'] - $orderItem->quantity;

                if ($product->quantity < $stockAdjustment) {
                    return response()->json(['message' => "Not enough stock for product: {$product->name}"], 400);
                }

                $orderItem->update(['quantity' => $item['quantity']]);
                $product->decrement('quantity', $stockAdjustment);

                $totalPrice += $orderItem->price * $item['quantity'];
            } else {
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

        $order->update(['total_price' => $totalPrice]);
        $user->notify(new OrderNotification('updated', $order));

        return response()->json(['message' => 'Order updated successfully', 'order' => $order->load('orderItems.product')]);
    }
}
