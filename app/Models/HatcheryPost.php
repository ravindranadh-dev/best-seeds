<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HatcheryPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hatchery_id',
        'title',
        'description',
        'media_path',
        'media_type',
        'hashtags',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'media_type' => 'string',
    ];

    public function hatchery()
    {
        return $this->belongsTo(Hatchery::class);
    }
}

