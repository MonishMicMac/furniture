<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use Validator;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        // Use the Validator facade to validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15|unique:login,mobile_number',
            'email' => 'required|email|unique:login,email',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'billing_address' => 'required|string|max:255', // Validate billing address
            'delivery_address' => 'required|string|max:255', // Validate delivery address
            'password' => 'required|string|min:8|confirmed', // Validate password (min length of 8, must be confirmed)
            'profile_img_path' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate profile image
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Create a new user entry
        $user = new Login(); 
        $user->name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->pincode = $request->pincode;
        $user->billing_address = $request->billing_address; // Save billing address
        $user->delivery_address = $request->delivery_address; // Save delivery address
        $user->password = bcrypt($request->password); // Hash the password before saving
        $user->profile_img_path = $this->uploadProfileImage($request); // Handle profile image upload
        $user->status = '0'; // Default status
        $user->save();
    
        return response()->json(['message' => 'User created successfully!'], 201);
    }

    /**
     * Handle profile image upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    private function uploadProfileImage(Request $request)
    {
        if ($request->hasFile('profile_img_path')) {
            $file = $request->file('profile_img_path');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/profile_images/' . $fileName;
            $file->move(public_path('uploads/profile_images'), $fileName);
            return $filePath;
        }
        return null; // If no file is uploaded, return null
    }
}
