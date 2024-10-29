<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
class OrderController 
{
    
    public function storeOrder(Request $request)
    {

        $request->validate([
            'status' => 'required|in:pending,success,cancelled'
        ]);

        
        $userId = Auth::id();

        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

       
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity; 
        });

      
        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => $request->status
        ]);



        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price 
            ]);
        }

       
        $cartItems->each->delete(); 

      
       

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order], 200);
    }


    public function showUserOrders($userId)
    {
        $orders = Order::where('user_id', $userId)->with('orderItems.product')->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for this user.'], 404);
        }

        return response()->json([
            'user_id' => $userId,
            'orders' => $orders
        ]);
    }






}
