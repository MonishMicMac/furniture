<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show() {
        // Fetch data from the 'login' table
        $users = DB::table('login')->where('status', '0')->get();

        // Pass the data to the view
        return view('users', ['users' => $users]);
    }

    public function edit($id) {

        // dd("hai");
        // Fetch user data for the given ID
        $user = DB::table('login')->where('id', $id)->first();
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        return view('edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'state' => 'required',
            'pincode' => 'required',
        ]);

        // Update the user information
        $updated = DB::table('login')->where('id', $id)->update($request->only(['name', 'mobile_number', 'email', 'address', 'state', 'pincode']));

        if ($updated) {
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        }

        return redirect()->route('users.index')->with('error', 'Failed to update user');
    }

    public function destroy($id) {
        // Update the user status to -1 instead of deleting
        $updated = DB::table('login')->where('id', $id)->update(['status' => '1']);
    
        if ($updated) {
            return redirect()->route('users.index')->with('success', 'User status updated successfully');
        }
    
        return redirect()->route('users.index')->with('error', 'Failed to update user status');
    }
    
}
