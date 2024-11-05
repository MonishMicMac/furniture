<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'product_image_path',
        'action',
        'brand', 
        'discount_price', // Add discount price field if included in migration
        'reviews_count', // Add reviews count field if included in migration
        'average_rating', // Add average rating field if included in migration
        'category_id',
    ];

    public function productMappings()
{
    return $this->hasMany(ProductMapping::class);
}


}
