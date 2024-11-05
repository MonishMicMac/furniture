<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMapping;
use Illuminate\Http\Request;

class ProductMappingController extends Controller
{
    public function create()
{
    // Fetch all active products and categories
    $products = Product::where('action', '0')->get();
    $categories = Category::where('action', '0')->get();

    // Get all mappings, grouped by product
    $productMappings = ProductMapping::with(['product', 'category'])
        ->get()
        ->groupBy('product_id'); // Group by product for easy table display

    return view('product_mapping', compact('products', 'categories', 'productMappings'));
}


public function store(Request $request)
{
    // Validate the request to ensure a product and category are selected
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'category_id' => 'required|exists:categories,id',
    ]);

    // Create or update the mapping
    ProductMapping::updateOrCreate(
        [
            'product_id' => $request->product_id,
            'category_id' => $request->category_id,
        ],
        ['action' => '0'] // Set action as active, or modify as needed
    );

    return redirect()->route('product_mapping.create')->with('success', 'Product mapping saved successfully!');
}


}
