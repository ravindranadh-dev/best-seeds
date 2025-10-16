<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HatcheryUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'caption',
        'hashtags',
        'media_files',
        'media_type',
    ];

     protected $casts = [
        'hashtags' => 'array',
        'media_files' => 'array',
        'media_type'  => 'array',
    ];

    /**
     * Relation with User (Vendor)
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
