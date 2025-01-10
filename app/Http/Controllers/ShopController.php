<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function show(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            $statusCode = 404;
            return response()->json(['message' => 'Shop not found', 'status_code' => $statusCode], $statusCode);
        }

        return response()->json($shop);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'shop_photo' => 'nullable|image|max:2048', 
        ]);

        $user = $request->user();

        if ($user->shop) {
            $statusCode = 400;
            return response()->json(['message' => 'User already has a shop', 'status_code' => $statusCode], $statusCode);
        }

        $data = $request->only(['name', 'location']);

        if ($request->hasFile('shop_photo')) {
            $data['shop_photo'] = $request->file('shop_photo')->store('shop_photos', 'public');
        }

        $shop = $user->shop()->create($data);

        return response()->json($shop, 201);
    }

    public function update(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            $statusCode = 404;
            return response()->json(['message' => 'Shop not found', 'status_code' => $statusCode], $statusCode);
        }

        $request->validate([
            'name' => 'nullable|string',
            'location' => 'nullable|string',
            'shop_photo' => 'nullable|image|max:2048', 
        ]);

        $data = $request->only(['name', 'location']);

        $shopPhoto = $shop->shop_photo;
        if ($request->hasFile('shop_photo')) {
            if ($shopPhoto) {
                Storage::disk('public')->delete($shopPhoto);
            }
            $data['shop_photo'] = $request->file('shop_photo')->store('shop_photos', 'public');
        }

        $shop->update($data);

        return response()->json($shop);
    }

    public function destroy(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            $statusCode = 404;
            return response()->json(['message' => 'Shop not found', 'status_code' => $statusCode], $statusCode);
        }

        if ($shop->shop_photo) {
            Storage::disk('public')->delete($shop->shop_photo);
        }

        $shop->delete();

        return response()->json(['message' => 'Shop deleted successfully']);
    }

    public function index()
    {
        $shops = Shop::all();
        return response()->json($shops);
    }

    public function show_with_products($id)
    {
        $shop = Shop::with('products')->find($id);

        if (!$shop) {
            $statusCode = 404;
            return response()->json(['message' => 'Shop not found', 'status_code' => $statusCode], $statusCode);
        }

        return response()->json([
            'shop' => $shop,
            'products' => $shop->products,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $name = $request->input('name');

        $shops = Shop::where('name', 'LIKE', "%$name%")->get();
        $products = Product::where('name', 'LIKE', "%$name%")->get();

        return response()->json([
            'shops' => $shops,
            'products' => $products,
        ]);
    }
}
    