<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Vendor;
use App\Models\HatcheryLocation;
use App\Models\Hatchery;
use App\Models\HatcheryCategory;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Booking::latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }


    public function create()
    {
        $vendors=Vendor::all();
        $locations=HatcheryLocation::all();
        $data = Hatchery::orderByDesc('created_at')->get();
        $allcategories=HatcheryCategory::all();
        return view('admin.bookings.create',compact('vendors','locations','data','allcategories'));
    }


public function store(Request $request)
{
    try {
        // Validate request

        $validated = $request->validate([
            'vendor_id' => 'required',
            'vehicle_images' => 'nullable|array',
            'vehicle_images.*' => 'image',
            'customer_id' => 'required',
            'customer_name' => 'required',
            'delivery_location' => 'nullable|string|max:255',
            'hatchery_name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'no_of_pieces' => 'nullable|integer',
            'dropping_location' => 'nullable',
            'packing_date' => 'nullable|date',
            'categories' => 'nullable|array',
            'count' => 'nullable|integer',
            'driver_name' => 'required|string|max:255',
            'driver_mobile' => 'required|string|max:20',
            'vehicle_number' => 'required|string|max:50',
            'vehicle_started_date' => 'nullable|date',
        ]);

        // Handle image uploads
        if ($request->hasFile('vehicle_images')) {
            $paths = [];
            foreach ($request->file('vehicle_images') as $image) {
                $paths[] = $image->store('vehicle_images', 'public');
            }
            $validated['vehicle_images'] = json_encode($paths);
        }

        // Create booking
        Booking::create($validated);

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking created successfully.');

    } catch (\Illuminate\Validation\ValidationException $e) {

        // Validation errors
        return redirect()->back()
                         ->withErrors($e->errors())
                         ->withInput();
    } catch (\Exception $e) {

        // General errors
        \Log::error('Booking creation failed: '.$e->getMessage());
        return redirect()->back()
                         ->with('error', 'Something went wrong. Please try again.')
                         ->withInput();
    }
}



    public function edit(Booking $booking)
    {
         $vendors=Vendor::all();
        $locations=HatcheryLocation::all();
        $data = Hatchery::orderByDesc('created_at')->get();
        $allcategories=HatcheryCategory::all();
        return view('admin.bookings.edit',compact('vendors','booking','locations','data','allcategories'));
        // return view('admin.bookings.edit', compact('booking'));
    }


    public function update(Request $request, Booking $booking)
{
    try {
        // Validate input
           dd( $request->all());
        $validated = $request->validate([
            'vendor_id' => 'required|integer',
            'vehicle_images' => 'nullable|array',
            'vehicle_images.*' => 'image|max:2048',
            'customer_id' => 'nullable|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:20',
            'delivery_location' => 'nullable|string|max:255',
            'hatchery_name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'no_of_pieces' => 'nullable|integer',
            'dropping_location' => 'nullable|string|max:255',
            'packing_date' => 'nullable|date',
            'categories' => 'nullable|array', // Expect array from multi-select
            'count' => 'nullable|integer',
            'driver_name' => 'required|string|max:255',
            'driver_mobile' => 'required|string|max:20',
            'vehicle_number' => 'required|string|max:50',
            'vehicle_started_date' => 'nullable|date',
        ]);

        // Handle multi-select categories
        if (isset($validated['categories']) && is_array($validated['categories'])) {
            $validated['categories'] = json_encode($validated['categories']);
        }

        // Merge new images with existing ones
        if ($request->hasFile('vehicle_images')) {
            $newPaths = [];
            foreach ($request->file('vehicle_images') as $image) {
                $newPaths[] = $image->store('vehicle_images', 'public');
            }

            $existing = json_decode($booking->vehicle_images ?? '[]', true);
            $validated['vehicle_images'] = json_encode(array_merge($existing, $newPaths));
        }

        $booking->update($validated);

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking updated successfully.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        dd($e);
        return redirect()->back()
                         ->withErrors($e->errors())
                         ->withInput();
    } catch (\Exception $e) {
        dd($e);
        \Log::error('Booking update failed: '.$e->getMessage());
        return redirect()->back()
                         ->with('error', 'Something went wrong. Please try again.')
                         ->withInput();
    }
}


    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted.');
    }

    public function getFormattedBookings()
    {
        return Booking::latest()->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'vendor_id' => $booking->vendor_id,
                'customer' => [
                    'id' => $booking->customer_id,
                    'name' => $booking->customer_name,
                    'mobile' => $booking->customer_mobile,
                ],
                'hatchery' => [
                    'name' => $booking->hatchery_name,
                    'delivery_location' => $booking->delivery_location,
                ],
                'driver' => [
                    'name' => $booking->driver_name,
                    'mobile' => $booking->driver_mobile,
                ],
                'vehicle' => [
                    'number' => $booking->vehicle_number,
                    'started_date' => optional($booking->vehicle_started_date)->format('Y-m-d'),
                ],
                'packing_date' => optional($booking->packing_date)->format('Y-m-d'),
                'unit' => $booking->unit,
                'no_of_pieces' => $booking->no_of_pieces,
                'dropping_location' => $booking->dropping_location,
                'categories' => json_decode($booking->categories ?? '[]'),
                'count' => $booking->count,
                'vehicle_images' => json_decode($booking->vehicle_images ?? '[]'),
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $booking->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
