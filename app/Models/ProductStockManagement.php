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



    /**
     * Get the current_balance for a given product_id and product_code.
     *
     * @param int $productId
     * @param string $productCode
     * @return float|null
     */
    public static function getCurrentBalance($productId, $productCode)
    {
        // Fetch the latest record based on product_id and product_code in descending order by id
        $record = self::where('product_id', $productId)
                      ->where('product_code', $productCode)
                      ->orderBy('id', 'desc')
                      ->limit(1)
                      ->first();

        // Return the current_balance if the record is found, null otherwise
        return $record ? $record->current_stock : null;
    }
    
    
}
