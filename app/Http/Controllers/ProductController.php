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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
    
        $shop = $request->user()->shop;
    
        if (!$shop) {
            $statusCode = 403;
            return response()->json(['message' => 'You do not own a shop','$status_code'=>$statusCode], $statusCode);
        }
    
        $data = $request->only(['name', 'description', 'quantity', 'price']);
    
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('products', 'public'); // Store in 'storage/app/public/products'
            $data['photo'] = $filePath;
        }
    
        $product = $shop->products()->create($data);
    
        return response()->json($product, 201);
    }
    

    // Show all products for the authenticated user's shop
    public function myProducts(Request $request)
    {
         $shop = $request->user()->shop;

          if (!$shop) {
            $statusCode = 403;
              return response()->json(['message' => 'You do not own a shop','$status_code'=>$statusCode], $statusCode);         }

         $products = $shop->products;

        return response()->json($products);
   }



    // Update a product in the authenticated user's shop
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            $statusCode = 404;
            return response()->json(['message' => 'Product not found',
            '$status_code'=>$statusCode], $statusCode);
        }
    
        if ($product->shop_id !== $request->user()->shop->id) {
            $statusCode = 403;
            return response()->json(['message' => 'Unauthorized',
            '$status_code'=>$statusCode], $statusCode);
        }
    
        $request->validate([
            'name' => 'string',
            'description' => 'string',
            'quantity' => 'integer|min:0',
            'price' => 'numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
    
        $data = $request->only(['name', 'description', 'quantity', 'price']);
    
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('products', 'public');
            $data['photo'] = $filePath;
        }
    
        $product->update($data);
    
        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }
    





   // Delete a product in the authenticated user's shop
    public function destroy(Request $request, $id)
    {
    $product = Product::find($id);

    if (!$product) {
        $statusCode = 404;
        return response()->json(['message' => 'Product not found',
        '$status_code'=>$statusCode], $statusCode);
    }

    // Check if the product belongs to the authenticated user's shop
    if ($product->shop_id !== $request->user()->shop->id) {
        $statusCode = 403;
        return response()->json(['message' => 'Unauthorized',
        '$status_code'=>$statusCode], $statusCode);
    }

    $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
    }

public function show($id)
{
    $product = Product::with('shop')->find($id);

    if (!$product) {
        $statusCode = 404;
        return response()->json(['message' => 'Product not found',
        '$status_code'=>$statusCode], $statusCode);
    }

    $product->photo_url = $product->photo ? asset('storage/' . $product->photo) : null;

    return response()->json($product);
}



}
