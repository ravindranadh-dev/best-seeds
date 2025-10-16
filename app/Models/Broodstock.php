<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broodstock extends Model
{
    use HasFactory;

    protected $fillable = [
         'vendor_id',
        'hatchery_name',
        'category',
        'location',
        'count',
        'available_date',
        'packing_date',
        'images',
    ];

   protected $casts = [
        'category' => 'array',
        'images'   => 'array',
    ];

     public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
