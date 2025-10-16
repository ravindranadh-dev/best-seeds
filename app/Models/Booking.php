<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'vehicle_images',
        'customer_id',
        'customer_name',
        'customer_mobile',
        'delivery_location',
        'hatchery_name',
        'categories',
        'count',
        'driver_name',
        'driver_mobile',
        'vehicle_number',
        'vehicle_started_date',
        'unit',                 // newly added
        'no_of_pieces',         // newly added
        'dropping_location',    // newly added
        'packing_date',         // newly added
    ];

    // ✅ Cast JSON fields
    protected $casts = [
        'vehicle_images' => 'array',
        'categories'     => 'array',
        'vehicle_started_date' => 'date',
        'packing_date' => 'date',
    ];


     // ✅ Relationship with Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function seed()
    {
        return $this->belongsTo(Seed::class);
    }

}
