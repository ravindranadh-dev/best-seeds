<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'path',
        'mime',
        'size',
        'preview',
        'loadable_id',
        'loadable_type',
        'uploader_id',
        'uploader_type',
    ];

    /**
     * 🔹 Polymorphic relation (Seed, Hatchery, Booking, etc.)
     */
    public function loadable()
    {
        return $this->morphTo();
    }

    /**
     * 🔹 Polymorphic relation (Vendor, User/Farmer, Admin)
     */
    public function uploader()
    {
        return $this->morphTo();
    }

    /**
     * 🔹 Accessor to return full URL for the file
     */
    public function getPathAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }

    /**
     * 🔹 Accessor to return full URL for preview if available
     */
    public function getPreviewAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}
