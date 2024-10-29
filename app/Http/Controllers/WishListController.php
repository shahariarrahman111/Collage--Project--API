<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishList;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishListController
{
    
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();

        
        $wishlistItem = Wishlist::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Product added to wishlist', 'wishlist_item' => $wishlistItem], 201);
    }

    
    public function getUserWishlist()
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->with('product')->get();

        if ($wishlist->isEmpty()) {
            return response()->json(['message' => 'Your wishlist is empty'], 404);
        }

        return response()->json($wishlist);
    }

    
    public function removeFromWishlist($id)
    {
        $wishlistItem = Wishlist::findOrFail($id);
        $wishlistItem->delete();

        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }



}
