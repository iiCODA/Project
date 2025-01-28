<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebShopController extends Controller
{

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'shop_photo' => 'nullable|image|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Prepare the data
        $data = $request->only(['name', 'location']);
        if ($request->hasFile('shop_photo')) {
            $data['shop_photo'] = $request->file('shop_photo')->store('shop_photos', 'public');
        }

        // Create the shop
        $shop = $user->shop()->create($data);

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Shop created successfully!');
    }

    public function update(Request $request, $id)
    {
        // Retrieve the shop
        $shop = Auth::user()->shop;

        if (!$shop || $shop->id !== (int)$id) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Shop not found or unauthorized access.']);
        }

        // Validate the input
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'shop_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare the updated data
        $data = $request->only(['name', 'location']);

        // Handle shop photo upload
        if ($request->hasFile('shop_photo')) {
            // Delete the old photo if it exists
            if ($shop->shop_photo) {
                Storage::disk('public')->delete($shop->shop_photo);
            }

            // Store the new photo
            $data['shop_photo'] = $request->file('shop_photo')->store('shop_photos', 'public');
        }

        // Update the shop
        $shop->update($data);

        return redirect()->route('dashboard')->with('success', 'Shop updated successfully!');
    }

    public function edit($id)
    {
        // Retrieve the shop
        $shop = Auth::user()->shop;

        if (!$shop || $shop->id !== (int)$id) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Shop not found or unauthorized access.']);
        }

        // Pass the shop details to the edit view
        return view('shop.edit', compact('shop'));
    }



    public function destroy(Request $request)
    {
        // Retrieve the authenticated user's shop
        $shop = Auth::user()->shop;

        if (!$shop) {
            // Redirect back with an error message if no shop exists
            return redirect()->route('dashboard')->withErrors(['error' => 'Shop not found.']);
        }

        // Delete the shop photo if it exists
        if ($shop->shop_photo) {
            Storage::disk('public')->delete($shop->shop_photo);
        }

        // Delete the shop
        $shop->delete();

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Shop deleted successfully!');
    }

    public function showorders(Request $request)
    {
        // Get the shop associated with the authenticated user
        $shop = $request->user()->shop;

        if (!$shop) {
            return redirect()->route('dashboard')->withErrors(['error' => 'You do not have a shop.']);
        }

        // Fetch the orders related to the shop's products
        $orderItems = OrderItem::whereHas('product.shop', function ($query) use ($shop) {
            $query->where('id', $shop->id);
        })->get();

        // Group order items by order ID
        $orders = [];
        foreach ($orderItems as $orderItem) {
            $orderId = $orderItem->order_id;
            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'order_id' => $orderId,
                    'order_date' => $orderItem->order->created_at->toDateTimeString(),
                    'items' => [],
                ];
            }
            $orders[$orderId]['items'][] = [
                'order_item_id' => $orderItem->id,
                'product_id' => $orderItem->product_id,
                'product_name' => $orderItem->product->name,
                'quantity' => $orderItem->quantity,
                'price' => $orderItem->price,
            ];
        }

        // Pass the orders to a Blade view
        return view('orders.index', ['orders' => $orders]);
    }
}
