<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('action', '0')->get();
    
        $products = Product::where('products.action', '0') // Specify the table
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name') // Select product fields and category name
        ->get();
        return view('products', compact('products', 'categories')); // Pass both products and categories to the view
    }
    

    public function store(Request $request)
{
    // Define validation rules
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric|lt:price', // Ensure discount price is less than the original price
        'quantity' => 'required|integer|min:0',
        'brand' => 'nullable|string|max:255',
        'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'required|exists:categories,id',
    ]);

    // Check if the validation fails
    if ($validator->fails()) {

        // dd($validator->messages());

        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
    }

   
    // Check if the file is present
    if ($request->hasFile('product_image')) {
        try {

           
            // Store the image and get its path
            $imagePath = $request->file('product_image')->store('images', 'public');

            // Create the product
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price, // Add discount price
                'quantity' => $request->quantity,
                'brand' => $request->brand, // Add brand
                
                'product_image_path' => $imagePath,
                'category_id' => $request->category_id
            ]);
           
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {

            dd($e->getMessage());
            \Log::error('Image upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Image upload failed. Please try again.');
        }
    } else {
        \Log::error('Image upload failed: No file was uploaded.');
        return redirect()->back()->with('error', 'No image file was uploaded.');
    }
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

    

}
