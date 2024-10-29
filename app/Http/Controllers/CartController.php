<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController
{
    

    public function addToCart(Request $request)
    {

        $request->validate([

            "product_id"=> "required|exists:products,id",
            "quantity"=> "required|integer|min:1"

        ]);


        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401); 
        }

        $cartItem = Cart::where('user_id', $userId)
                   ->where('product_id', $request->product_id)->first();
        
        
        if ($cartItem){

            $cartItem->quantity += $request->quantity;
            $cartItem->save();


        }else{

            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);

        }   
        
        return response()->json(['message' => 'Product added to cart successfully'], 201);
                                      
    }


    public function viewCart()
    {
       
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        return response()->json(['cart' => $cartItems]);
    }



}
