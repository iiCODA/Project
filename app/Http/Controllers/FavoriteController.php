<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function addFavorite(Request $request, $productId)
    {
        $user = $request->user();
        $product = Product::findOrFail($productId);

        if (!$user->favorites()->where('product_id', $productId)->exists()) {
            $user->favorites()->attach($productId);
            return response()->json(['message' => 'Product added to favorites'], 201);
        }

        return response()->json(['message' => 'Product is already in favorites'], 400);
    }

    public function removeFavorite(Request $request, $productId)
    {
        $user = $request->user();
        $product = Product::findOrFail($productId);

        if ($user->favorites()->where('product_id', $productId)->exists()) {
            $user->favorites()->detach($productId);
            return response()->json(['message' => 'Product removed from favorites'], 200);
        }

        return response()->json(['message' => 'Product is not in favorites'], 404);
    }

    public function getFavorites(Request $request)
    {
        $user = $request->user();
        $favorites = $user->favorites()->with('favoritedBy')->get();

        return response()->json($favorites);
    }
}
