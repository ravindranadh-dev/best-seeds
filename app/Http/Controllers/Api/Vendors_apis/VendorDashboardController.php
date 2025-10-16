<?php

namespace App\Http\Controllers\Api\Vendors_apis;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seed;
use App\Models\Hatchery;
use App\Models\Broodstock;
use App\Models\Vehicle;
use App\Models\HatcheryUpdate;
use Exception;
use Illuminate\Support\Facades\Log;

class VendorDashboardController extends Controller
{
    /**
     * 📌 Dashboard Summary (Slide 3)
     * - Seed Brands count
     * - Hatchery Updates count
     * - Broodstock count
     * - Vehicle Availability count
     */
    public function summary(Request $request)
    {
        try {
            $vendorId = Auth::id();

            // 🔹 Fetch counts
            $seedCount       = Seed::where('vendor_id', $vendorId)->count();
            $updateCount     = HatcheryUpdate::where('vendor_id', $vendorId)->count();
            $broodstockCount = Broodstock::where('vendor_id', $vendorId)->count();
            $vehicleCount    = Booking::where('vendor_id', $vendorId)->count();

            return response()->json([
                'message' => '✅ Dashboard summary fetched successfully',
                'data'    => [
                    'seed_brands'      => $seedCount,
                    'hatchery_updates' => $updateCount,
                    'broodstock'       => $broodstockCount,
                    'vehicles'         => $vehicleCount,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error("VendorDashboardController@summary failed", [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'error'   => true,
                'method'  => 'summary',
                'message' => 'Failed to fetch dashboard summary',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
