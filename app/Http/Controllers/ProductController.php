<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use DB;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        // Fetch categories and subcategories where action = 0
        $categories = Category::where('action', '0')->get();
        $subcategories = Subcategory::where('action', '0')->get();
    
        // Fetch products where action = 0 without joining on category_id
        $products = Product::where('products.action', '0')->get();
    
        // Pass products, categories, and subcategories to the view
        return view('products', compact('products', 'categories', 'subcategories'));
    }
    


    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:products,product_code|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|lt:price',
            'quantity' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:255',
            'warranty_month' => 'nullable|integer|min:0',
            'min_order_qty' => 'nullable|integer|min:0',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'sub_category_id' => 'required|exists:subcategories,id', // Ensure the column name matches
        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Store product and associated subcategory
        $product = Product::create($request->only([
            'name', 'product_code', 'price', 'discount_price', 'quantity', 'brand', 
            'sub_category_id', 'warranty_month', 'min_order_qty'
        ]));
    
        // Store images if available
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $imagePath = $image->store('images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
    


    public function destroy(Product $product)
    {
        $product->update(['action' => '1']);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('action', '0')->get();

    return view('product_edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric|lt:price', // Discount price should be less than price
        'description' => 'nullable|string',
        'quantity' => 'required|integer|min:0',
        'brand' => 'nullable|string|max:255',
        'average_rating' => 'nullable|numeric|min:0|max:5',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image field
        'category_id' => 'required|exists:categories,id'
    ]);

    // Find the product
    $product = Product::findOrFail($id);

    // Handle image upload if a new file is uploaded
    if ($request->hasFile('product_image')) {
        // Delete old image if it exists
        if ($product->product_image_path && \Storage::exists('public/' . $product->product_image_path)) {
            \Storage::delete('public/' . $product->product_image_path);
        }

        // Store new image
        $imagePath = $request->file('product_image')->store('images', 'public');
        $product->product_image_path = $imagePath;
    }

    // Update other product fields
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->discount_price = $request->discount_price;
    $product->quantity = $request->quantity;
    $product->brand = $request->brand;
    $product->average_rating = $request->average_rating;
    $product->category_id = $request->category_id; 

    $product->save(); // Save the updated product

    return redirect()->route('products.index')->with('success', 'Product updated successfully!');
}

    
public function showProduct()
{
    // Get all products with their associated image paths (without using relationships)
    $products = DB::table('products')
        ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
        ->select('products.*', 'product_images.image_path')
        ->get();

    // Debug the result to ensure we are getting the data correctly
    // dd($products);

    // Return the view with the products data
    return view('product_show', compact('products'));
}

}
