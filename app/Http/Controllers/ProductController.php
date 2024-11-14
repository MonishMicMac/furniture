<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStockManagement;
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
            'description' => 'required|string|max:5000',  // Add validation rule for description
        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Store product and associated subcategory
        $product = Product::create($request->only([
            'name', 'product_code', 'price', 'discount_price', 'quantity', 'brand', 
            'sub_category_id', 'warranty_month', 'min_order_qty', 'description'  // Include description in the create method
        ]));
        
        $productStockData = [
            'product_id' => $product->id,
            'product_code' => $product->product_code,
            'open_balance' => $product->quantity,   // Use $product->quantity
            'total_stock' => $product->quantity,    // Use $product->quantity
            'balance_stock' => $product->quantity,  // Use $product->quantity
            'current_stock' => $product->quantity,  // Use $product->quantity
        ];
        
        ProductStockManagement::create($productStockData);

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
    
    
    // ProductController.php
    public function removeImage($productId, Request $request)
    {
       
        $product = Product::findOrFail($productId);

        // Assuming the image is stored in 'public' disk
        $imagePath = $request->image_path;
        
        // Remove image from storage
        if (Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }

        // Optionally, remove the image reference from the database if needed
        // $product->images()->where('path', $imagePath)->delete();

        return response()->json(['success' => true]);
    }
    
    

    public function destroy(Product $product)
    {
        $product->update(['action' => '1']);
        return redirect()->to('/productsshow')->with('success', 'Product updated successfully!');
    }

    public function edit(Product $product)
    {
        // Get all subcategories
        $subcategories = Subcategory::all();
    
        // Get the product images for the current product
        $productImages = DB::table('product_images')
            ->where('product_id', $product->id)
            ->pluck('image_path'); // Fetch all image paths for this product
    
        return view('product_edit', compact('product', 'subcategories', 'productImages'));
    }
    

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric|lt:price', // Discount price should be less than price
            'description' => 'nullable|string',
           
            'brand' => 'nullable|string|max:255',
            
            'sub_category_id' => 'required|integer|exists:subcategories,id', // Validate sub_category_id
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow multiple images
            'warranty_month' => 'nullable|integer|min:0',
        ]);
    
        // Find the product
        $product = Product::findOrFail($id);
    
        // Update other product fields
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
       
        $product->brand = $request->brand;
        $product->warranty_month = $request->warranty_month;
       
        $product->sub_category_id = $request->sub_category_id;  // Set sub_category_id
    
        // Save the updated product data
        $product->save();
    
        // Handle image upload if new files are uploaded
        if ($request->hasFile('product_images')) {
            // Remove old images if needed
            $existingImages = $product->images; // Assuming you have a relation for `images`
            foreach ($existingImages as $existingImage) {
                if (\Storage::exists('public/' . $existingImage->image_path)) {
                    \Storage::delete('public/' . $existingImage->image_path);
                }
                $existingImage->delete(); // Remove from the database
            }
    
            // Store new images
            foreach ($request->file('product_images') as $image) {
                $imagePath = $image->store('images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }
    
        // Redirect with success message
        return redirect()->to('/productsshow')->with('success', 'Product updated successfully!');
    }
    


    
    public function showProduct()
    {
        $products = DB::table('products')
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->leftJoin('subcategories', 'products.sub_category_id', '=', 'subcategories.id') // Join with subcategories table
            ->leftJoin('categories', 'subcategories.category_id', '=', 'categories.id') // Join with categories table using category_id from subcategories table
            ->select(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.discount_price',
                'products.quantity',
                'products.brand',
                'products.average_rating',
                'products.sub_category_id',
                'products.warranty_month',
                'products.min_order_qty',
                'categories.name as category_name', // Select category name
                'subcategories.name as subcategory_name', // Select subcategory name
                DB::raw('GROUP_CONCAT(product_images.image_path) as images')
            )
            ->where('products.action', '=', '0') // Add where clause to filter by action
            ->groupBy(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.discount_price',
                'products.quantity',
                'products.brand',
                'products.average_rating',
                'products.sub_category_id',
                'products.warranty_month',
                'products.min_order_qty',
                'categories.name', // Add this field to the group by clause
                'subcategories.name' // Add this field to the group by clause
            )
            ->get();
    
        return view('product_show', compact('products'));
    }
    


}
