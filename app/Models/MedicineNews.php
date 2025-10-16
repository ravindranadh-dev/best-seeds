<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineNews extends Model
{
    use HasFactory;

     protected $fillable = [
        'title',
        'image',
        'description',
    ];

    // Return full URL for medicine image
    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : null;
    }
}
