<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    // Show the list of subcategories and the create form
    public function index()
    {
        $categories = Category::where('action', '0')->get(); // Only active categories
        $subcategories = Subcategory::with('category')->where('action', '0')->get(); // Only active subcategories
        return view('subcategory', compact('subcategories', 'categories'));
    }

    // Store a new subcategory
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure category exists
        ]);

        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory added successfully.');
    }

    // Edit a subcategory
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::where('action', '0')->get(); // Only active categories
        return view('subcategories_edit', compact('subcategory', 'categories'));
    }

    // Update a subcategory
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subcategory->update($request->only(['name', 'category_id']));

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    // Soft delete a subcategory (update action to 1)
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->action = '1'; // Mark as deleted
        $subcategory->save();

        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}