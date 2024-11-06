<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request) {
        $approvalStatus = $request->input('approval_status');
       
        // Check if approval status filter is applied
        if ($approvalStatus !== null) {
            // Filter users by status 0 (active) and the selected approval status
            $users = DB::table('login')
                        ->where('status', '0')
                        ->where('approval_status', $approvalStatus)
                        ->get();
        } else {
            // Show all active users (status 0)
            $users = DB::table('login')->where('status', '0')->get();
        }
    
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
            'gst_no' => 'nullable',
            'shop_name' => 'nullable',
            'pan_no' => 'nullable',
            'otp' => 'nullable',
           
        ]);

        // Update the user information
        $updated = DB::table('login')->where('id', $id)->update($request->only([
            'name', 'mobile_number', 'email', 'address', 'state', 'pincode', 
            'gst_no', 'shop_name', 'pan_no', 'otp'
        ]));
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
    public function approve($id) {
        $user = Login::find($id);
        if ($user) {
            $user->approval_status = '1'; // 1 for Approved
            $user->save();
            return redirect()->route('users.index')->with('success', 'User approved successfully.');
        }
        return redirect()->route('users.index')->with('error', 'User not found.');
    }
    
    public function decline($id) {
        $user = Login::find($id);
        if ($user) {
            $user->approval_status = '2'; // 2 for Declined
            $user->save();
            return redirect()->route('users.index')->with('success', 'User declined successfully.');
        }
        return redirect()->route('users.index')->with('error', 'User not found.');
    }

    public function showinmodal($id)
{
    $user = Login::find($id);
    return response()->json($user);
}


}
