<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AppBanner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function showCategory(){

        $categories = Category::where('action', '0')->get();

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 200);
    }


    public function showSubCategory()
    {
      
    
      
        $subcategories = Subcategory::with('category')->where('action', '0')->get();
    
      
        return response()->json([
            'success' => true,
            'message' => 'Categories and Subcategories retrieved successfully',
            'data' => [
                'subcategories' => $subcategories
            ]
        ], 200);
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
            ->where('products.action', '=', '0')
            ->where('products.liquidation_status', '=', '0')  // Add where clause to filter by action
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
    
        // Format data to show in desired structure
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'quantity' => $product->quantity,
                'brand' => $product->brand,
                'average_rating' => $product->average_rating,
                'subcategory_id' => $product->sub_category_id,
                'warranty_month' => $product->warranty_month,
                'min_order_qty' => $product->min_order_qty,
                'category_name' => $product->category_name,
                'subcategory_name' => $product->subcategory_name,
                'images' => explode(',', $product->images) // Convert image paths into an array
            ];
        });
    
        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }
    

    public function show_liq_Product()
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
                'products.liquatation_price',
                'categories.name as category_name', // Select category name
                'subcategories.name as subcategory_name', // Select subcategory name
                DB::raw('GROUP_CONCAT(product_images.image_path) as images')
            )
            ->where('products.action', '=', '0')
            ->where('products.liquidation_status', '=', '1')  // Add where clause to filter by action
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
                'products.liquatation_price',
                'categories.name', // Add this field to the group by clause
                'subcategories.name' // Add this field to the group by clause
            )
            ->get();
    
        // Format data to show in desired structure
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'quantity' => $product->quantity,
                'brand' => $product->brand,
                'average_rating' => $product->average_rating,
                'subcategory_id' => $product->sub_category_id,
                'warranty_month' => $product->warranty_month,
                'min_order_qty' => $product->min_order_qty,
                'liquatation_price' => $product->liquatation_price,
                'category_name' => $product->category_name,
                'subcategory_name' => $product->subcategory_name,
                'images' => explode(',', $product->images) // Convert image paths into an array
            ];
        });
    
        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }

    public function show_app_banner()
    {
        // Retrieve only the 'img_path' and 'type' fields where action is 0
        $banners = AppBanner::where('action', '0')
            ->select('img_path', 'type')  // Select only 'img_path' and 'type'
            ->get();
    
        // Return the data as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Banners retrieved successfully',
            'data' => $banners
        ], 200);
    }
    
    
}
