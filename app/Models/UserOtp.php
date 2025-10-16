<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'otp_code',
        'expires_at',
        'verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified'   => 'boolean',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}