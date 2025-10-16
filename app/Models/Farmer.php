<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Farmer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'mobile',
        'language',
        'role',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function otps()
    {
        return $this->hasMany(UserOtp::class);
    }
}

//     public function galleries()
// {
//     return $this->morphMany(Gallery::class, 'loadable');
// }


