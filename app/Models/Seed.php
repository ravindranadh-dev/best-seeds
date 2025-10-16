<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    use HasFactory;

     protected $fillable = [
        'vendor_id',
        'hatchery_id',
        'categories',
        'description',
        'locations',
        'broad_stock',
        'stock_available_date',
        'price',
        'seed_images',
        'seed_videos',
    ];

     protected $casts = [
        'categories' => 'array',
        'locations'  => 'array',
        'seed_images'=> 'array',
        'seed_videos'=> 'array',
        'stock_available_date' => 'date',
    ];

    // 🔹 Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function hatchery()
    {
        return $this->belongsTo(Hatchery::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
