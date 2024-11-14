<?php

namespace App\Http\Controllers;

use App\Models\AppBanner;
use Illuminate\Http\Request;

class AppBannerController extends Controller
{
   
    public function create()
    {
        $banners = AppBanner::where('action', '0')->get(); // Get only banners where action is 0
        return view('banner', compact('banners'));
    }
    



public function store(Request $request)
{

  
    $request->validate([
        'img_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'type' => 'required|string',
        // Action is automatically set to 0 in the controller, no need to validate
    ]);

    // Upload image
    if ($request->hasFile('img_path')) {
        $imagePath = $request->file('img_path')->store('app_banners', 'public');
    }

    // Store the banner with action set to 0 by default
    AppBanner::create([
        'img_path' => $imagePath,
        'type' => $request->type,
        'action' => '0', // Always inactive by default
    ]);

    return redirect()->route('banners.create')->with('success', 'Banner created successfully.');
}

public function edit($id)
{
    $banner = AppBanner::findOrFail($id); // Fetch the banner to edit
    return view('banner_edit', compact('banner')); // Adjust view name as needed
}

public function update(Request $request, $id)
{
    $request->validate([
        'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'type' => 'required|string',
    ]);

    $banner = AppBanner::findOrFail($id);

    // Update the image if a new one is uploaded
    if ($request->hasFile('img_path')) {
        $imagePath = $request->file('img_path')->store('app_banners', 'public');
        $banner->img_path = $imagePath;
    }

    // Update the banner details
    $banner->type = $request->type;
    $banner->save();

    return redirect()->route('banners.create')->with('success', 'Banner updated successfully.');
}

public function destroy($id)
{
    $banner = AppBanner::findOrFail($id);

    // Instead of deleting, change action to 1
    $banner->action = '1';
    $banner->save();

    return redirect()->route('banners.create')->with('success', 'Banner removed successfully.');
}

}
