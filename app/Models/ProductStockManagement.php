<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockManagement extends Model
{
    use HasFactory;

    protected $table = 'product_stock_management';

    // Specify the fillable attributes
    protected $fillable = [
        'product_id',       // Allow mass assignment for product_id
        'product_code',
        'open_balance',
        'total_stock',
        'balance_stock',
        'current_stock',
        'dispatch',
        'sales',
        'closing_stock',
        'other_sale',
        'canceled_stock',
        'decline_stock'
    ];

    // Add relationships if necessary, for example, with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
