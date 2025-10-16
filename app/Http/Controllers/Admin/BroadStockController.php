<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BroadStock;
use App\Models\HatcheryCategory;
use App\Models\HatcheryLocation;
use App\Models\Hatchery;



use Illuminate\Support\Facades\Storage;

class BroadStockController extends Controller
{
    public function index()
    {

   $broadStocks = BroadStock::with(['hatchery', 'category', 'location'])
                    ->latest()
                    ->paginate(10);
        return view('admin.broad-stocks.index', compact('broadStocks'));
    }

    public function create()
    {

        $data = Hatchery::orderByDesc('created_at')->get();
        $categories = HatcheryCategory::all();
        $locations = HatcheryLocation::all();
        return view('admin.broad-stocks.create', compact('categories', 'locations','data'));
    }

   public function store(Request $request)
{
    // Validate input
    $request->validate([
        'hatchery_id' => 'required|exists:hatcheries,id',
        'category_id' => 'required|exists:hatchery_categories,id',
        'location_id' => 'required|exists:hatchery_locations,id',
        'count' => 'required|integer|min:1',
        'available_date' => 'required|date',
        'packing_date' => 'required|date',
        'seed_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    // Handle image upload
    $imagePath = null;
    $imageType = null;

    if ($request->hasFile('seed_image')) {
        $image = $request->file('seed_image');
        $filename = 'seed_image_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/broad_stock/'), $filename);

        $imagePath = 'uploads/broad_stock/' . $filename;
        $imageType = $image->getClientOriginalExtension();
    }

    // Create record
    BroadStock::create([
        'hatchery_id' => $request->hatchery_id,
        'category_id' => $request->category_id,
        'location_id' => $request->location_id,
        'count' => $request->count,
        'available_date' => $request->available_date,
        'packing_date' => $request->packing_date,
        'seed_image_path' => $imagePath,
        'seed_image_type' => $imageType,
        'is_active' => true,
    ]);

    return redirect()->route('broad-stocks.index')->with('success', 'Broad stock created successfully.');
}


    public function edit(BroadStock $broadStock)
    {

        $data = Hatchery::orderByDesc('created_at')->get();
        $categories = HatcheryCategory::all();
        $locations = HatcheryLocation::all();
        return view('admin.broad-stocks.edit', compact('data','broadStock', 'categories', 'locations'));
    }

    public function update(Request $request, BroadStock $broadStock)
    {
        $request->validate([
            'hatchery_id' => 'required|exists:hatcheries,id',
        'category_id' => 'required|exists:hatchery_categories,id',
        'location_id' => 'required|exists:hatchery_locations,id',
            'count' => 'required|integer|min:1',
            'available_date' => 'required|date',
            'packing_date' => 'required|date',
            'seed_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('seed_image')) {
            if ($broadStock->seed_image_path) {
                Storage::disk('public')->delete($broadStock->seed_image_path);
            }
            $image = $request->file('seed_image');
            $broadStock->seed_image_path = $image->store('seeds', 'public');
            $broadStock->seed_image_type = $image->extension();
        }

        $broadStock->update([
            'hatchery_name' => $request->hatchery_name,
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'count' => $request->count,
            'available_date' => $request->available_date,
            'packing_date' => $request->packing_date,
        ]);

        return redirect()->route('broad-stocks.index')->with('success', 'Broad stock updated successfully.');
    }

    public function destroy(BroadStock $broadStock)
    {
        if ($broadStock->seed_image_path) {
            Storage::disk('public')->delete($broadStock->seed_image_path);
        }

        $broadStock->delete();

        return redirect()->route('broad-stocks.index')->with('success', 'Broad stock deleted successfully.');
    }
}

