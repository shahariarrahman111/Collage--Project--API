<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\User;
use Exception;

class ProfileController
{

    public function CreateProfile(Request $request)
    {
        try{

            $profile = Profile::create([
                'user_id' => Auth::id(),
                'division' => $request->division,
                'city' => $request->city,
                'upozila' => $request->upozila,
                'postOffice' => $request->postOffice
            ]);

            return response()->json(['message' => 'Profile created successfully', 'profile' => $profile], 201);
       


        }catch(Exception $e){

            return response()->json(['message'=> $e->getMessage()]);
        }

    }


    public function ShowProfile(){

        $user = User::with('profile')->find(Auth::id());

        if (!$user || !$user->profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $userData = [
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'userName' => $user->userName,
            'email' => $user->email,
            'phone' => $user->phone 
        ];

        $profileData = [
            'division' => $user->profile->division,
            'city' => $user->profile->city,
            'upozila' => $user->profile->upozila,
            'postOffice' => $user->profile->postOffice
        ];

        return response()->json([
            'user' => $userData,
            'profile' => $profileData,
        ]);
    }


    public function UpdateProfile(Request $request){

       try{

        $request->validate([
            'division' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'upozila' => 'nullable|string|max:100',
            'postOffice' => 'nullable|string|max:100'
        ]);

        $profile = Profile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['division', 'city', 'upozila','postOffice'])
        );

        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);


       }catch(Exception $e){

        return response()->json(['message'=> $e->getMessage()], 400);

       }

    }



    public function UserProfileList()
    {
        $users = User::all(); 
        return response()->json($users); 
    }

    
    public function adminShowUserProfile($id)
    {
        $user = User::findOrFail($id); 
        return response()->json($user); 
    }
    
}
