<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;

    protected $table = 'login';

    protected $fillable = [
        'name', 
        'mobile_number',
        'email',
        'address',
        'state',
        'pincode',
        'gst_no',
        'shop_name',
        'pan_no',
        'otp',
        'approval_status',
        'status',
        'billing_address',  // Added new column
        'delivery_address', // Added new column
        'password',         // Added new column
        'profile_img_path'  // Added new column
    ];


    /**
     * Check if a record with the given ID has approval_status = '1' and status = '0'.
     *
     * @param int $id
     * @return bool
     */
    public static function isApprovedAndInactive($id)
    {
        // Fetch the record by ID
        $record = self::where('id', $id)
                      ->where('approval_status', '1')
                      ->where('status', '0')
                      ->first();

        // Return true if the record exists, false otherwise
        return $record ? true : false;
    }
    
}
