<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierStock extends Model
{
    use HasFactory;

     protected $fillable = [
        'hatchery_id',
        'vendor_id',
        'available_quantity',
        'packing_start_date',
        'location',
        'image',
    ];

    protected $casts = [
        'packing_start_date' => 'date',
    ];

    // 🔹 Relationships
    public function hatchery()
    {
        return $this->belongsTo(Hatchery::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // 🔹 Accessor: full URL for image
    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}
