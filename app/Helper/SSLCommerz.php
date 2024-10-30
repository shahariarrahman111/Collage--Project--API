<?php

namespace App\Helper;

use App\Models\Invoice;
use App\Models\SslCommerceAccount;
use Exception;
use Illuminate\Support\Facades\Http;

class SSLCommerz
{

   static function  InitiatePayment($Profile,$payable,$tran_id,$user_email): array
   {
      try {
         $ssl = SslCommerceAccount::first();
         
         if (!$ssl) {
             return ['error' => 'SSL Account configuration not found.'];
         }
         
         $response = Http::asForm()->post($ssl->init_url, [
             "store_id" => $ssl->store_id,
             "store_passwd" => $ssl->store_passwd,
             "total_amount" => $payable,
             "currency" => $ssl->currency,
             "tran_id" => $tran_id,
             "success_url" => "$ssl->success_url?tran_id=$tran_id",
             "fail_url" => "$ssl->fail_url?tran_id=$tran_id",
             "cancel_url" => "$ssl->cancel_url?tran_id=$tran_id",
             "ipn_url" => $ssl->ipn_url,
             "cus_name" => $Profile->user->name,
             "cus_email" => $user_email,
             "cus_add1" => $Profile->division . ', ' . $Profile->upozila,
             "cus_add2" => $Profile->postOffice, 
             "cus_city" => $Profile->city, 
             "cus_state" => $Profile->division,
             "cus_postcode" => "1200",
             "cus_country" => "Bangladesh",  
             "cus_phone" => $Profile->user->phone,  
             "cus_fax" => $Profile->user->phone,
             "shipping_method" => "YES",
             "ship_name" => $Profile->ship_name ?? 'Default Name',
             "ship_add1" => $Profile->ship_add ?? 'Default Address',
             "ship_add2" => $Profile->ship_add,
             "ship_city" => $Profile->ship_city ?? 'Default City',
             "ship_state" => $Profile->ship_city,
             "ship_country" => $Profile->ship_country ?? 'Bangladesh',
             "ship_postcode" => "12000",
             "product_name" => "Book Shop Product",
             "product_category" => "Book Shop Category",
             "product_profile" => "Book Shop Profile",
             "product_amount" => $payable,
         ]);
 
         // রেসপন্স চেক
         if (!$response->successful()) {
             return ['error' => 'Payment initiation failed.'];
         }
 
         return $response->json();
     } catch (Exception $e) {
         return ['error' => $e->getMessage()];
     }

   }



    static function InitiateSuccess($tran_id):int{
        Invoice::where(['tran_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>'Success']);
        return 1;
    }








    static function InitiateFail($tran_id):int{
       Invoice::where(['tran_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>'Fail']);
       return 1;
    }



    static function InitiateCancel($tran_id):int{
        Invoice::where(['tran_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>'Cancel']);
        return 1;
    }

    static function InitiateIPN($tran_id,$status,$val_id):int{
        Invoice::where(['tran_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>$status,'val_id'=>$val_id]);
        return 1;
    }
}