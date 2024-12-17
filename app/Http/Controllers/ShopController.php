<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function show(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        return response()->json($shop);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);

        $user = $request->user();

        if ($user->shop) {
            return response()->json(['message' => 'User already has a shop'], 400);
        }

        $shop = $user->shop()->create($request->only(['name', 'location']));

        return response()->json($shop, 201);
    }

    public function update(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $request->validate([
            'name' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $shop->update($request->only(['name', 'location']));

        return response()->json($shop);
    }

    public function destroy(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $shop->delete();

        return response()->json(['message' => 'Shop deleted successfully']);
    }

    public function index()
{
    $shops = Shop::all();
    return response()->json($shops);
}


// Show shop details and its products
public function show_with_products($id)
{
    $shop = Shop::with('products')->find($id);

    if (!$shop) {
        return response()->json(['message' => 'Shop not found'], 404);
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

    // Search for shops and products by name
    $shops = Shop::where('name', 'LIKE', "%$name%")->get();
    $products = Product::where('name', 'LIKE', "%$name%")->get();

    return response()->json([
        'shops' => $shops,
        'products' => $products,
    ]);
}



}
