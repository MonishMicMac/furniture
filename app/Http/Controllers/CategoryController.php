<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Store a new category
    public function store(Request $request)
    {
        // Use the Validator facade to validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the category if validation passes
        Category::create([
            'name' => $request->name,
            'action' => $request->input('action', '0'), // Set default action to 0
        ]);
        return redirect()->back()->with('success', 'Category added successfully.');
    }

    // Method to retrieve categories
    public function index()
    {
        $categories = Category::where('action', '0')->get();
        return view('categories', compact('categories')); // Ensure you have the correct view path
    }
    public function edit(Category $category)
    {
        return view('categories_edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->only(['name']));
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // Delete the category
    public function destroy(Category $category)
    {
        // Update the action attribute to '1'
        $category->action = '1'; // Assuming 'action' is an integer field in your database
        $category->save(); // Save the changes
    
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }


}
