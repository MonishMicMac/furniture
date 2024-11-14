<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    // Specify the table name (if it's different from the plural form of the model)
    protected $table = 'promocode';  // This is correct if the table is named 'promocode'


    // Define which fields are mass assignable (columns in your table)
    protected $fillable = ['code', 'expire_date', 'discount_type', 'discount', 'from_date', 'to_date', 'action'];


    // Optionally, specify any date fields to be automatically cast as Carbon instances
    protected $dates = ['from_date', 'expire_date'];

    // You can optionally add mutators or accessors if you want custom date/time formats.
    // For example, formatting dates as Y-m-d

    public function getFromDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function getExpireDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
