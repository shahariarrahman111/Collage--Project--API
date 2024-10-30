<?php

namespace App\Http\Controllers;

use App\Helper\SSLCommerz;
use App\Models\Profile;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Cart;
use App\Models\SSLCommereceAcount; 
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController 
{
    
    function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user(); 
            $user_id = $user->id;
            $user_email = $user->email;

            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';

          
            $Profile = Profile::where('user_id', '=',  $user_id)->with('user')->first();
            

            if ($Profile) {
                
                $cus_details = "Name: {$Profile->user->name}, Address: {$Profile->division}, {$Profile->upozila}, City: {$Profile->city}, Phone: {$Profile->user->phone}";
                $ship_details = "Name: {$Profile->user->name}, Address: {$Profile->division}, {$Profile->upozila}, City: {$Profile->city}, Phone: {$Profile->user->phone}";
            } else {
                return response()->json(['message'=> 'Profile Not Found']);
            }
  
            $total = 0;
            $cartList = Cart::where('user_id', '=', $user_id)->get();
            foreach ($cartList as $cartItem) {
                $total += $cartItem->price;
            }

            $vat = ($total * 3) / 100;
            $payable = $total + $vat;

          
            $invoice = Invoice::create([
                'user_id' => $user_id,
                'tran_id' => $tran_id,
                'cus_details'=> $cus_details,
                'delivery_status'=> $delivery_status,
                'ship_details'=> $ship_details,
                'amount' => $payable, 
                'currency' => 'BDT', 
                'payment_status' => $payment_status,
            ]);

            $invoiceID = $invoice->id;

           
            foreach ($cartList as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'product_id' => $EachProduct['product_id'],
                    'user_id' => $user_id,
                    'quantity' => $EachProduct['quantity'],
                    'sale_price' => $EachProduct['price'],
                ]);
            }

            
            
            $paymentMethod = SSLCommerz::InitiatePayment($Profile, $payable, $tran_id, $user_email);

            DB::commit();

            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'paymentMethod' => $paymentMethod,
                    'payable' => $payable,
                    'vat' => $vat,
                    'total' => $total
                ]
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    
    function InvoiceList(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        
        $invoices = Invoice::where('user_id', $user->id)->get();

        return response()->json(['status' => 'success', 'data' => $invoices]);
    }
    

    
    function InvoiceProductList(Request $request, $invoice_id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
      
        if (!is_numeric($invoice_id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid invoice ID'], 400);
        }  
        
        
        $invoiceProducts = InvoiceProduct::where('user_id', $user->id)
            ->where('invoice_id', (int)$invoice_id)->with('product')->get();

        return response()->json(['status' => 'success', 'data' => $invoiceProducts]);


    }

    
    function PaymentSuccess(Request $request)
    {
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
    }

    
    function PaymentCancel(Request $request)
    {
        SSLCommerz::InitiateCancel($request->query('tran_id'));
        return redirect('/profile');
    }

   
    function PaymentFail(Request $request)
    {
        SSLCommerz::InitiateFail($request->query('tran_id'));
        return redirect('/profile');
    }


    function PaymentIPN(Request $request)
    {
        return SSLCommerz::InitiateIPN($request->input('tran_id'), $request->input('status'), $request->input('val_id'));
    }
}
