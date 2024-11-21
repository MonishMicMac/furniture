<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStockManagement;
use Illuminate\Http\Request;

class LiquatationController extends Controller
{
   
    
    public function index(Request $request)
    {
        // Fetch all products that are not deleted (action = 0) and their latest stock
        $products = Product::where('action', '0')
            ->with(['latestStock'])  // Eager load the latestStock relationship
            ->get();
        
        // Add the latest current stock to each product (using the relation)
        foreach ($products as $product) {
            // Check if there is any stock data available
            $product->latest_current_stock = $product->latestStock->current_stock ?? 0;
        }
    
        // dd( $product);
        return view('liquatation', compact('products'));
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'liquidation_status' => 'nullable|in:0,1',
            'liquidation_price' => 'nullable|numeric|min:0',  // Ensure liquidation price is not below 0
        ]);
    
        // Find the product by id
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
    
        // Check if liquidation price is greater than discount price
        if ($request->has('liquidation_price') && $request->input('liquidation_price') > $product->discount_price) {
            return response()->json(['success' => false, 'message' => 'Liquidation price cannot be greater than discount price.']);
        }
    
        // Update only the liquidation fields
        $product->liquidation_status = $request->input('liquidation_status', $product->liquidation_status);
        $product->liquatation_price = $request->input('liquidation_price', $product->liquatation_price);
        
        // Save the changes
        $product->save();
    
        // Return success response
        return response()->json(['success' => true]);
    }
    
    public function showReport(Request $request)
    {
       
       
        $productType = $request->input('product_type');
    
       
        $products = Product::where('action', '0')
        ->where('liquidation_status', $productType) 
        ->with(['latestStock'])  
        ->get();
    
  
    foreach ($products as $product) {
       
        $product->latest_current_stock = $product->latestStock->current_stock ?? 0;
    }
        
        return view('liquatation_filter', compact('products'));
    }
    
}
