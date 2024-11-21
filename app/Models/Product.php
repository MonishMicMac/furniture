<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Fillable columns (columns that can be mass-assigned)
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'product_image_path',
        'action',
        'brand',
        'discount_price',
        'reviews_count',
        'average_rating',
       
        'sub_category_id',           // Added sub_category_id
        'product_code',  // Added product_code_availability
        'warranty_month',             // Added warranty_month
        'min_order_qty',              // Added min_order_qty
        'liquidation_status',  
        'liquatation_quantity',       // Added liquidation_status
    ];
    

    /**
     * Define the relationship with the ProductMapping model.
     */
  // In Product.php model
public function images()
{
    return $this->hasMany(ProductImage::class);
}

public function stock()
{
    return $this->hasMany(ProductStockManagement::class, 'product_id');
}

public function latestStock()
{
    return $this->hasOne(ProductStockManagement::class, 'product_id')->orderBy('created_at', 'desc');
}

}
