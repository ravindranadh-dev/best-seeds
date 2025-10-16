<?php

namespace App\Http\Controllers\Api\Vendors_apis;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Validation\ValidationException;

class VendorAuthController extends Controller
{

// ravindra
public function login(Request $request)
{
    try {

        $request->validate([
            'best_seeds_id' => 'required|string|exists:vendors,best_seeds_id',
            'password'      => 'required|string',
        ]);


        $vendor = Vendor::where('best_seeds_id', $request->best_seeds_id)
                        ->where('role', 'hatchery')
                        ->first();

        if (!$vendor) {
            return response()->json(['message' => 'Invalid hatchery credentials'], 401);
        }

        if (Hash::check($request->password, $vendor->password)) {
            $token = $vendor->createToken('api_token')->plainTextToken;

            return response()->json([
                'message' => 'Hatchery login successful',
                'token'   => $token,
                'vendor'  => $vendor,
            ]);
        }

        if ($vendor->is_first_login == 0) {
            $tempPassword = Crypt::decryptString($vendor->temp_password_encrypted);



            if ($request->password === $tempPassword) {
                return response()->json([
                    'message' => 'First login — please set a new password.',
                    'require_password_reset' => true,
                    'vendor_id' => $vendor->id,
                ], 200);
            }
        }

        return response()->json(['message' => 'Invalid hatchery credentials'], 401);

    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);

    } catch (\Exception $e) {
        \Log::error('Hatchery Login failed: ' . $e->getMessage(), [
            'best_seeds_id' => $request->best_seeds_id
        ]);
        return response()->json(['message' => 'Login failed'], 500);
    }
}

public function setnewpassword(Request $request)
{
    $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    try {
        $vendor = Vendor::findOrFail($request->vendor_id);


        if (!$vendor->is_first_login) {
            return response()->json(['message' => 'Password already updated.'], 400);
        }


        $vendor->password = Hash::make($request->new_password);
        $vendor->is_first_login = false;
        $vendor->temp_password_encrypted = null;
        $vendor->save();

        return response()->json(['message' => 'Password updated successfully. You can now log in.']);

    } catch (\Exception $e) {
        \Log::error('Error updating password: ' . $e->getMessage());
        return response()->json(['message' => 'Password update failed.'], 500);
    }
}



    // public function login(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'best_seeds_id' => 'required|exists:vendors,best_seeds_id', // ✅ changed to vendors table
    //             'password'      => 'required',
    //         ]);

    //         $vendor = Vendor::where('best_seeds_id', $request->best_seeds_id) // ✅ using Vendor model
    //                     ->where('role', 'hatchery')
    //                     ->first();

    //         if (!$vendor || !Hash::check($request->password, $vendor->password)) {
    //             return response()->json(['message' => 'Invalid hatchery credentials'], 401);
    //         }

    //         $token = $vendor->createToken('api_token')->plainTextToken;

    //         return response()->json([
    //             'message' => 'Hatchery login successful',
    //             'token'   => $token,
    //             'vendor'  => $vendor   // ✅ renamed
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Login failed',
    //             'error'   => $e->getMessage(),
    //             'line'    => $e->getLine(),
    //             'file'    => $e->getFile()
    //         ], 500);
    //     }
    // }

    /**
     * ======================
     * Logout
     * ======================
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ], 500);
        }
    }

    /**
     * ======================
     * Profile
     * ======================
     */
    public function profile(Request $request)
    {
    try {
        $vendor = $request->user();
        // $vendor->profile_image = $vendor->profile_image
        //     ? asset('storage/'.$vendor->profile_image)
        //     : null;

        return response()->json($vendor);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Fetching profile failed',
            'error'   => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile()
        ], 500);
    }
}


    // 🔹 Update Personal Information
    public function updateProfile(Request $request)
    {
        try {
            $vendor = $request->user();


        // 🔹 Reusable closure for mobile number uniqueness check across both columns
        $uniqueMobile = function ($attribute, $value, $fail) use ($vendor) {
            $exists = \App\Models\Vendor::where('id', '!=', $vendor->id)
                ->where(function ($q) use ($value) {
                    $q->where('mobile', $value)
                      ->orWhere('alternate_mobile', $value);
                })->exists();

            if ($exists) {
                $fail("The {$attribute} is already taken");
            }
        };

            $request->validate([
                // 'name'             => 'required|string|max:255',
                'name'             => 'nullable|string|max:255',
                // 'mobile'           => 'nullable|string|regex:/^\+[1-9]\d{1,14}$/|different:alternate_mobile',
                // 'alternate_mobile' => 'nullable|string|regex:/^\+[1-9]\d{1,14}$/|different:mobile',
                'mobile'           => ['nullable','string','regex:/^\+[1-9]\d{1,14}$/',$uniqueMobile],
                'alternate_mobile' => ['nullable','string','regex:/^\+[1-9]\d{1,14}$/',$uniqueMobile],
                'address'          => 'nullable|string|max:500',
                'pincode'          => 'nullable|string|max:10',
                'profile_image'    => 'nullable|image|max:2048', // any image type, max 2MB
            ]);

            // if ($request->hasFile('profile_image')) {
            //     $path = $request->file('profile_image')->store('vendor_profiles', 'public');
            //     $vendor->profile_image = $path;
            // }

            if ($request->hasFile('profile_image')) {
                // 🔹 Custom filename: datemonthyear + vendor_name + random string + extension
                $file = $request->file('profile_image');
                $filename = date('dmY') . '_' . preg_replace('/\s+/', '_', strtolower($vendor->name)) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('vendor_profiles', $filename, 'public');

                $vendor->profile_image = $path;
            }


            $vendor->name             = $request->name;
            $vendor->mobile           = $request->mobile;
            $vendor->alternate_mobile = $request->alternate_mobile;
            $vendor->address          = $request->address;
            $vendor->pincode          = $request->pincode;

            $vendor->save();

            return response()->json([
                    'message' => 'Profile updated successfully',
                    'vendor'  => [
                        'id'               => $vendor->id,
                        'name'             => $vendor->name,
                        'mobile'           => $vendor->mobile,
                        'alternate_mobile' => $vendor->alternate_mobile,
                        'address'          => $vendor->address,
                        'pincode'          => $vendor->pincode,
                        'best_seeds_id'    => $vendor->best_seeds_id,
                        'role'             => $vendor->role,
                        'created_at'       => $vendor->created_at,
                        'updated_at'       => $vendor->updated_at,
                        'is_profile_complete' => !empty($vendor->name) && !empty($vendor->mobile) && !empty($vendor->address),
                        // 🔥 Fix here: always return full image URL
                        // 'profile_image'    => $vendor->profile_image
                        //                         ? asset('storage/'.$vendor->profile_image)
                        //                         : null,
                        'profile_image'    => $vendor->profile_image,

                    ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Profile update failed', 'error' => $e->getMessage()], 500);
        }
    }




}
