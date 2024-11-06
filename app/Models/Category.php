<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'action'];
 
    
    public function productMappings()
{
    return $this->hasMany(ProductMapping::class);
}


public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    
}
