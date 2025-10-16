<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hatchery extends Model
{
    use HasFactory;

    protected $fillable = [
        'hatchery_name',
        'category',         // JSON array (optional if normalized)
        'location',         // JSON array or string (optional if normalized)
        'lat',
        'lng',
        'vendor_id',
        'category_id',      // normalized foreign key
        'location_id',      // normalized foreign key
        'opening_time',
        'closing_time',
        'image',            // JSON array of image paths
        'brand',
    ];

    protected $casts = [
        'category'      => 'array',
        'location'      => 'array',
        'image'         => 'array',
        'opening_time'  => 'datetime:H:i',
        'closing_time'  => 'datetime:H:i',
    ];

    // 🔹 Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categoryRef()
    {
        return $this->belongsTo(HatcheryCategory::class, 'category_id');
    }
public function locationRef()
{
    return $this->belongsTo(HatcheryLocation::class, 'location_id');
}



    public function seeds()
    {
        return $this->hasMany(Seed::class);
    }

    public function updates()
    {
        return $this->hasMany(HatcheryUpdate::class);
    }

    // 🔹 Accessors
    public function getImageAttribute($value)
    {
        $decoded = is_array($value) ? $value : json_decode($value, true);
        return is_array($decoded)
            ? array_map(fn($path) => url('storage/' . $path), $decoded)
            : [];
    }

    public function getCategoryAttribute($value)
    {
        return is_array($value) ? $value : json_decode($value, true);
    }

    public function getLocationAttribute($value)
    {
        return is_array($value) ? $value : json_decode($value, true);
    }
}
