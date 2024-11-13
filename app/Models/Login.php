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
}
