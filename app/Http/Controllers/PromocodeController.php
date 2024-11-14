<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promocode;

class PromocodeController extends Controller
{
    public function create()
    {
        // Fetch all promo codes from the database
        $promocodes = Promocode::all();
        
        // Pass the promo codes to the view
        return view('promocode_create', compact('promocodes'));
    }
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'code' => 'required|string|unique:promocode',
            'expire_date' => 'required|date',
            'discount_type' => 'required|in:0,1',
            'discount' => 'required|numeric',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date', // Keep this if you want to use it
        ]);
    
        // Handle the date fields if provided
        $validated['from_date'] = isset($validated['from_date']) ? \Carbon\Carbon::parse($validated['from_date'])->format('Y-m-d') : null;
        $validated['expire_date'] = \Carbon\Carbon::parse($validated['expire_date'])->format('Y-m-d');
        $validated['to_date'] = isset($validated['to_date']) ? \Carbon\Carbon::parse($validated['to_date'])->format('Y-m-d') : null;
    
        // Create a new promo code record
        Promocode::create($validated);
    
        // Redirect to a success page or back with a success message
        return redirect()->route('promocode.create')->with('success', 'Promo code created successfully!');
    }
    

    public function edit($id)
{
    $promocode = Promocode::findOrFail($id);
    return view('promocode_edit', compact('promocode'));
}

public function update(Request $request, $id)
{
    // Find the promocode by ID
    $promocode = Promocode::findOrFail($id);

    // Validate the input
    $validated = $request->validate([
        'code' => 'required|string|unique:promocode,code,' . $promocode->id, // Exclude the current promocode from uniqueness check
        'expire_date' => 'required|date',
        'discount_type' => 'required|in:0,1',
        'discount' => 'required|numeric',
        'from_date' => 'nullable|date',
        
    ]);

    // Debug the validated data before updating (you can remove this after debugging)
  

    // Handle the date fields if provided
    $validated['from_date'] = isset($validated['from_date']) ? \Carbon\Carbon::parse($validated['from_date'])->format('Y-m-d') : null;
    $validated['expire_date'] = \Carbon\Carbon::parse($validated['expire_date'])->format('Y-m-d');
    // $validated['to_date'] = isset($validated['to_date']) ? \Carbon\Carbon::parse($validated['to_date'])->format('Y-m-d') : null;

    // Update the promocode
    $promocode->update($validated);

    // Redirect back with success message
    return redirect()->route('promocode.create')->with('success', 'Promo code updated successfully!');
}






}
