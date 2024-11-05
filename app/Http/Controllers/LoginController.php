<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login; 
use Validator;// Ensure this matches your model

class LoginController extends Controller
{
    public function store(Request $request)
    {
        // Use the Validator facade
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15|unique:login,mobile_number',
            'email' => 'required|email|unique:login,email',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
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
        $user->status = '0'; // Default status
        $user->save();
    
        return response()->json(['message' => 'User created successfully!'], 201);
    }

}
