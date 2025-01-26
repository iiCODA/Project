<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebProductController extends Controller
{
    public function store(Request $request)
    {
        // Validate the product data
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);
    
        // Ensure the user owns a shop
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard')->withErrors(['error' => 'You do not own a shop.']);
        }
    
        // Prepare product data
        $data = $request->only(['name', 'description', 'quantity', 'price']);
    
        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('products', 'public');
            $data['photo'] = $filePath;
        }
    
        // Create the product
        $shop->products()->create($data);
    
        // Redirect back to the dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Product added successfully!');
    }
    
    public function update(Request $request, $id)
    {
        // Find the product
        $product = Product::find($id);
    
        if (!$product) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Product not found.']);
        }
    
        // Ensure the product belongs to the user's shop
        if ($product->shop_id !== Auth::user()->shop->id) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Unauthorized action.']);
        }
    
        // Validate the input
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Prepare the updated data
        $data = $request->only(['name', 'description', 'quantity', 'price']);
    
        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }
    
        // Update the product
        $product->update($data);
    
        return redirect()->route('dashboard')->with('success', 'Product updated successfully!');
    }
    
    public function edit($id)
{
    $product = Product::find($id);

    if (!$product) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Product not found.']);
    }

    // Ensure the product belongs to the authenticated user's shop
    if ($product->shop_id !== Auth::user()->shop->id) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Unauthorized action.']);
    }

    return view('product.edit', compact('product'));
}



    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Product not found.']);
        }
    
        // Ensure the product belongs to the user's shop
        if ($product->shop_id !== Auth::user()->shop->id) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Unauthorized action.']);
        }
    
        // Delete the product photo if it exists
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }
    
        // Delete the product
        $product->delete();
    
        // Redirect back to the dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }
    


}
