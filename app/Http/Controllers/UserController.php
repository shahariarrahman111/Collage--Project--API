<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController
{
    

    public function UserRegister(Request $request){

        try{

            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'userName' => 'required|string|max:255|unique:users,userName',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:15|unique:users,phone',
            ]);
    
            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'userName' => $request->userName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                
            ]);
    
            return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    

        }catch(Exception $e){
            return response()->json(['message'=> $e->getMessage()]);
        }
    }


    public function UserLogin(Request $request){

        try{

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                if ($user->role === 'admin') {
                    return response()->json(['message' => 'Login successful', 'redirect' => '/dashboard'], 200);
                } else {
                    return response()->json(['message' => 'Login successful', 'redirect' => '/home'], 200);
                }
            }

            return response()->json(['message' => 'Invalid credentials'], 401);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    public function UserLogout(Request $request){

        Auth::logout(); 

        $request->session()->invalidate();
        $request->session()->regenerate();

         $cookieName = 'jwt';

        return response()->json(['message'=> 'Logout Successful'], 200)->cookie( $cookieName, '', -1);

    }

}
