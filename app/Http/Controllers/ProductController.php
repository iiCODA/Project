<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Add a new product to the authenticated user's shop
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'You do not own a shop'], 403);
        }

        $product = $shop->products()->create($request->only(['name', 'description', 'quantity', 'price']));

        return response()->json($product, 201);
    }

    // Show all products for the authenticated user's shop
    public function myProducts(Request $request)
    {
         $shop = $request->user()->shop;

          if (!$shop) {
              return response()->json(['message' => 'You do not own a shop'], 403);
         }

         $products = $shop->products;

        return response()->json($products);
   }



    // Update a product in the authenticated user's shop
    public function update(Request $request, $id)
    {
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Check if the product belongs to the authenticated user's shop
    if ($product->shop_id !== $request->user()->shop->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
        'name' => 'string',
        'description' => 'string',
        'quantity' => 'integer|min:0',
        'price' => 'numeric|min:0',
    ]);

    $product->update($request->only(['name', 'description', 'quantity', 'price']));

    return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }





   // Delete a product in the authenticated user's shop
    public function destroy(Request $request, $id)
    {
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Check if the product belongs to the authenticated user's shop
    if ($product->shop_id !== $request->user()->shop->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
    }


    public function show($id)
{
    $product = Product::with('shop')->find($id); // Eager load the shop relationship

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    return response()->json($product);
}



}
