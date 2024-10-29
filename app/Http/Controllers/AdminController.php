<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminController
{
    
    public function index()
    {
        
        return view('admin.dashboard'); // 'admin.dashboard' 
    }


    public function dashboard()
    {
        $totalProducts = Product::count(); 
        $totalUsers = User::count(); 

        return view('admin.dashboard', compact('totalProducts', 'totalUsers'));
    }


    

    public function ShowAdminProfile() {
        $admin = Auth::user(); 
        // return view('admin.profile', compact('admin'));
        return response()->json([
            
            'data'=> $admin
        ]);
    }


    // Profile আপডেট করার জন্য ফাংশন
public function UpdateAdminProfile(Request $request) {
    $request->validate([
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        'phone' => 'required|string|max:15|unique:users,phone,' . Auth::id(),
    ]);

    try {
        $admin = User::find(Auth::id()); 

        
        if ($admin) {
           
            $admin->firstName = $request->input('firstName');
            $admin->lastName = $request->input('lastName');
            $admin->email = $request->input('email');
            $admin->phone = $request->input('phone');

            $admin->save(); 

            // return redirect()->route('admin.profile')->with('message', 'Profile updated successfully');

            return response()->json([
                'status'=> 'success',
                'message'=> 'Admin profile updated successfully!',
                'data'=>$admin
                ]);
        } else {
            // return redirect()->route('admin.profile')->withErrors(['error' => 'Admin not found.']);

            return response()->json([
                'status'=> 'error',
                'message'=> 'Admin profile not updated'
                ]);
        }
    } catch (Exception $e) {
       return response()->json(['error'=> $e->getMessage()]);
    }
}






}
