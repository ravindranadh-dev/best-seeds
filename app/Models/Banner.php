<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'redirect_url',
        'status', // active/inactive
    ];

      protected $casts = [
        'status' => 'boolean',
    ];

    // Return full URL for banner image
    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}
