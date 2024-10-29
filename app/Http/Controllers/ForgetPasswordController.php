<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ForgetPasswordController
{
   
    public function SendOTPCode(Request $request)
    {

        try{

            $request->validate([
                "email"=> "required|string|email"
            ]);

            $email = $request->input("email");
            $otp = rand(10000, 99999);
            $user = User::where("email", $email)->first();

            if(!$user){

                return response()->json([
                    "status"=> "error",
                    "message"=> "Invalid email address"
                ]);

            }else{

                Mail::to( $email)->send(new OTPMail($otp));

                $otpCreatedAt = now();

                User::where('email', '=', $email)->update(['otp'=>  $otp,  'updated_at' => now()]);

                
                $otpExpiryTime = now()->addMinutes(5);
                
                return response()->json([
                    'status'=> 'success',
                    'message'=> '5 Digit OTP Code Send Your Email',
                    'otp_expiry_time' => $otpExpiryTime 
                    ]);
            }


        }catch(Exception $e){
            return response()->json([
                'status'=> 'error',
                'message'=> $e->getMessage()
            ]);
        }

    }


    public function VerifyOTPCode(Request $request){

        try{

            $request->validate([

                'email'=> 'required|string|email',
                'otp'=> 'required|string|min:5'

            ]);

            $email = $request->input('email');
            $otp = $request->input('otp');

            $user = User::where('email', '=',  $email)
                    ->where('otp','=', $otp)->first();


             if (!$user){

                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Invalid OTP and eamil address'
                ]);

             }  
             
             
             $otpExpiryTime = Carbon::parse($user->updated_at)->addMinutes(5);


             if (Carbon::now()->greaterThan($otpExpiryTime)){

                return response()->json([
                    "status" => "error",
                    "message" => "OTP has expired. Please request a new one."
                ]);

             }else{

                return response()->json([
                    "status"=> "success",
                    "message"=> "OTP verify successful"
                ]);
             }


        }catch(Exception $e){
            return response()->json([
                "status"=> "error",
                "message"=> $e->getMessage()
            ]);
        }


    }


    function ResetPasswrod(Request $request){
        try{

            $request->validate([
                'password'=> 'required|string|min:8'
            ]);

            $id = Auth::id();
            $password = $request->input('password');

            User::where('id', '=', $id)->update(['password'=> Hash::make($password)]);

            return response()->json([
                'status'=> 'success',
                'message'=> 'ResetSuccessful'
            ]);

        }catch (Exception $e){
            return response()->json([
                'status'=> 'error',
                    'message'=> $e->getMessage()
            ]);
        }
    }



}
