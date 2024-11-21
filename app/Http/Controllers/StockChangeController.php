<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStockManagement;
use Illuminate\Http\Request;

class StockChangeController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the filter values from the request
        $selectedProduct = $request->input('product_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        // Fetch all products for the dropdown
        $products = Product::where('action', '0')->get();
    
        // Initialize the query for ProductStockManagement
        $stockQuery = ProductStockManagement::query();
    
        // If a product is selected, filter by product ID
        if ($selectedProduct) {
            $stockQuery->where('product_id', $selectedProduct);
        }
    
        // If date range is provided, filter by the date range
        if ($fromDate && $toDate) {
            // Adjust the toDate to include the entire day
            $adjustedToDate = \Carbon\Carbon::parse($toDate)->endOfDay();  // Convert to the end of the day
            $stockQuery->whereBetween('created_at', [$fromDate, $adjustedToDate]);
        }
    
        // Get the filtered stock data
        $filteredStock = $stockQuery->with('product')->get();
    
        return view('product_stock_change', compact('products', 'filteredStock', 'selectedProduct', 'fromDate', 'toDate'));
    }
   
 

    public function showAddStockForm()
    {
        
        $products = Product::where('action', '0')->get();

        return view('add_stock', compact('products'));
    }


    public function handleAddStock(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',  // Product must exist
            'quantity' => 'required|numeric|min:1'
        ]);
    
        // Get the product code from the `products` table using the product_id
        $product = Product::find($validated['product_id']);
        
        if (!$product) {
            // Handle the case where the product is not found (which should not happen due to validation)
            return redirect()->back()->with('error', 'Product not found!');
        }
    
        // Get the product code
        $productCode = $product->product_code;
    
        // Get today's date in Y-m-d format
        $today = \Carbon\Carbon::today()->toDateString();
        
        // Check if there is an existing record for today for the given product_id
        $existingStockRecord = ProductStockManagement::where('product_id', $validated['product_id'])
            ->whereDate('created_at', '=', $today)  // Only compare the date part (ignore time)
            ->first();
    
        if ($existingStockRecord) {
            // If a record exists for today, update it
            $existingStockRecord->dispatch += $validated['quantity'];
            $existingStockRecord->total_stock += $validated['quantity'];
            $existingStockRecord->current_stock += $validated['quantity'];
            $existingStockRecord->balance_stock += $validated['quantity'];  // Update balance stock if needed
    
            // Save the updated record
            $existingStockRecord->save();
        } else {
            // If no record exists for today, get the last available stock record
            $lastStockRecord = ProductStockManagement::where('product_id', $validated['product_id'])
                ->orderBy('created_at', 'desc')  // Get the most recent record
                ->first();
    
            // Check if a last stock record exists
            if ($lastStockRecord) {
                $openBalance = $lastStockRecord->current_stock;  // Use the previous day's current stock as open balance
            } else {
                $openBalance = 0;  // If no previous record, set open balance to 0
            }
    
            // dd($productCode);
            // Create a new stock record
            ProductStockManagement::create([
                'product_id' => $validated['product_id'],
                'product_code' => $productCode,  // Use the fetched product code
                'open_balance' => $openBalance,
                'dispatch' =>  $validated['quantity'],
                'total_stock' => $openBalance + $validated['quantity'],  // Add the new quantity
                'current_stock' => $openBalance + $validated['quantity'],  // Add the new quantity
                'balance_stock' => $openBalance + $validated['quantity'],  // Set balance stock initially
                // Add any other fields you want to initialize
            ]);
        }
    
        // Redirect back with a success message
        return redirect()->route('product_stock.add')->with('success', 'Stock updated successfully!');
    }
    

    
    
}
