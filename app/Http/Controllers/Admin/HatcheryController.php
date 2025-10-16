<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hatchery;
use App\Models\HatcheryCategory;
use App\Models\HatcheryLocation;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HatcheryController extends Controller
{

public function index()
{
    $data = Hatchery::orderByDesc('created_at')->get();

    $vendors = Vendor::pluck('name', 'id')->toArray();
    $categories =HatcheryCategory::pluck('category_name', 'id')->toArray();
    $locations =HatcheryLocation::pluck('location_name', 'id')->toArray();

    return view('admin.hatcheries.index', compact('data', 'vendors', 'categories', 'locations'));
}



    public function create()
    {
        $vendors = Vendor::all();
        $categories = HatcheryCategory::orderBy('priority')->get();
        $locations = HatcheryLocation::orderBy('priority')->get();

        return view('admin.hatcheries.create', compact('vendors', 'categories', 'locations'));
    }

public function store(Request $request)
{
    $request->validate([
        'hatchery_name' => 'required|string|max:255',
        'vendor_id'     => 'required|exists:vendors,id', // single vendor
        'category_id'   => 'required|array',
        'category_id.*' => 'exists:hatchery_categories,id',
        'location_id'   => 'required|array',
        'location_id.*' => 'exists:hatchery_locations,id',
        'opening_time'  => 'nullable|date_format:H:i',
        'closing_time'  => 'nullable|date_format:H:i',
        'image'         => 'nullable|array',
        'image.*'       => 'image|mimes:jpg,jpeg,png|max:2048',

    ]);

    try {
        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('hatcheries', 'public');
            }
        }

        // Create new Hatchery
        Hatchery::create([
            'hatchery_name' => $request->hatchery_name,
            'vendor_id'     => $request->vendor_id,
            'category_id'   => json_encode($request->category_id),
            'location_id'   => json_encode($request->location_id),
            'opening_time'  => $request->opening_time,
            'closing_time'  => $request->closing_time,
            'image'         => json_encode($imagePaths),


        ]);

        return redirect()->route('hatcheries.index')->with('success', 'Hatchery created successfully.');
    } catch (\Exception $e) {
        dd($e);
        Log::error('Error creating hatchery: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Failed to create hatchery.');
    }
}







    public function edit($id)
    {
        $hatchery = Hatchery::findOrFail($id);
        $vendors = Vendor::all();
        $categories = HatcheryCategory::orderBy('priority')->get();
        $locations = HatcheryLocation::orderBy('priority')->get();

        return view('admin.hatcheries.edit', compact('hatchery', 'vendors', 'categories', 'locations'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'hatchery_name' => 'required|string|max:255',
        'vendor_id'     => 'required|exists:vendors,id',
        'category_id'   => 'required|array',
        'category_id.*' => 'exists:hatchery_categories,id',
        'location_id'   => 'required|array',
        'location_id.*' => 'exists:hatchery_locations,id',
        'opening_time'  => 'nullable|date_format:H:i',
        'closing_time'  => 'nullable|date_format:H:i',
        'image'         => 'nullable|array',
        'image.*'       => 'image|mimes:jpg,jpeg,png|max:2048',
        // 'lat'           => 'nullable|numeric',
        // 'lng'           => 'nullable|numeric',
        // 'brand'         => 'nullable|string|max:255',
    ]);

    try {
        $hatchery = Hatchery::findOrFail($id);

        // Decode existing images
        $imagePaths = [];
        if ($hatchery->image) {
            if (is_string($hatchery->image)) {
                $imagePaths = json_decode($hatchery->image, true) ?? [];
            } elseif (is_array($hatchery->image)) {
                $imagePaths = $hatchery->image;
            }
        }

        // Handle new image uploads to public/uploads/hatcheries
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/hatcheries'), $filename);
                $imagePaths[] = 'uploads/hatcheries/' . $filename;
            }
        }

        // Update hatchery
        $hatchery->update([
            'hatchery_name' => $request->hatchery_name,
            'vendor_id'     => $request->vendor_id,
            'category_id'   => json_encode($request->category_id),
            'location_id'   => json_encode($request->location_id),
            'opening_time'  => $request->opening_time,
            'closing_time'  => $request->closing_time,
            'image'         => json_encode($imagePaths),
            // 'lat'           => $request->lat,
            // 'lng'           => $request->lng,
            // 'brand'         => $request->brand,
        ]);

        return redirect()->route('hatcheries.index')->with('success', 'Hatchery updated successfully.');
    } catch (\Exception $e) {
        Log::error('Error updating hatchery: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Failed to update hatchery.');
    }
}




    public function destroy($id)
    {
        try {
            $hatchery = Hatchery::findOrFail($id);
            $hatchery->delete();

            return redirect()->route('hatcheries.index')->with('success', 'Hatchery deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting hatchery: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete hatchery.');
        }
    }
}
