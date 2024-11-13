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
        'liquidation_status',         // Added liquidation_status
    ];
    

    /**
     * Define the relationship with the ProductMapping model.
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
