<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStockManagement;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function showClosingStockForm()
    {
        $products = Product::where('action', '0')->get(); // Fetch all active products
        return view('closing_stock', compact('products'));
    }

    // Handle the Closing Stock submission
    public function handleClosingStock(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1'  // Assuming you'll pass the closing quantity
        ]);
    
        // Get today's date
        $today = \Carbon\Carbon::today()->toDateString();
    
        // Fetch the latest stock data for the selected product
        $latestStock = ProductStockManagement::where('product_id', $validated['product_id'])
            ->whereDate('created_at', '=', $today)  // Check for today's date
            ->orderBy('id', 'desc')
            ->first();
    
        // Check if a record already exists for today
        if ($latestStock) {
            // Prevent multiple entries for the same day
            return redirect()->back()->with('error', 'You have already entered the closing balance for today.');
        }
    
        // Get the latest record to check current balance
        $latestRecord = ProductStockManagement::where('product_id', $validated['product_id'])
            ->orderBy('id', 'desc')
            ->first();
    
        $currentStock = $latestRecord ? $latestRecord->current_stock : 0;
    
        // Ensure the closing quantity does not exceed current balance
        if ($validated['quantity'] >= $currentStock) {
            return redirect()->back()->with('error', 'Closing balance cannot be greater than or equal the current stock.');
        }
    
        // If an entry exists, update existing record
        if ($latestStock) {
            // Update existing record
            $latestStock->other_sale = $latestStock->current_stock - $validated['quantity'];
            $latestStock->current_stock = $validated['quantity'];
            $latestStock->closing_stock += $validated['quantity'];
    
            // Ensure current_stock does not go below zero
            if ($latestStock->current_stock < 0) {
                $latestStock->current_stock = 0;
            }
    
            // Save the updated record
            $latestStock->save();
        } else {
            // Calculate the new values for a new record
            $newCurrentStock = max($currentStock - $validated['quantity'], 0);
            $newOtherSale = $newCurrentStock - $validated['quantity'];
    
            // Create a new record with updated values
            ProductStockManagement::create([
                'product_id' => $validated['product_id'],
                'product_code' => $latestRecord ? $latestRecord->product_code : 'N/A',
                'open_balance' => $currentStock,
                'total_stock' => $currentStock,
                'balance_stock' => $currentStock,
                'closing_stock' => $validated['quantity'],
                'current_stock' => $validated['quantity'],
                'other_sale' => $newCurrentStock,
                // Add any other fields you want to initialize
            ]);
        }
    
        // Redirect back with a success message
        return redirect()->route('product_stock.closing')->with('success', 'Stock updated successfully!');
    }
    
    

    
}
