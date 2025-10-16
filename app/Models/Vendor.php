<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class Vendor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'temp_password_encrypted',
        'password_reset_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_first_login' => 'boolean',
        'password_reset_token_expires_at' => 'datetime'
    ];

    protected $appends = ['is_profile_complete'];

    /**
     * Auto-generate best_seeds_id before creating vendor
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->best_seeds_id)) {
                $year = date('Y');
                $lastVendor = self::whereYear('created_at', $year)
                                ->orderBy('id', 'desc')
                                ->first();

                $nextId = $lastVendor ? intval(substr($lastVendor->best_seeds_id, -2)) + 1 : 1;

                $vendor->best_seeds_id = 'BS' . $year . str_pad($nextId, 2, '0', STR_PAD_LEFT);
            }

            // Set default values for new vendors
            $vendor->is_first_login = true;
        });
    }

    /**
     * Hash password automatically
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Check if profile is complete
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->name)
            && !empty($this->mobile)
            && !empty($this->address)
            && !empty($this->pincode);
    }

    /**
     * Accessor for is_profile_complete
     */
    public function getIsProfileCompleteAttribute(): bool
    {
        return $this->isProfileComplete();
    }

    public function getProfileImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /**
     * Check if this is the vendor's first login
     */
    public function isFirstLogin(): bool
    {
        return $this->is_first_login === true;
    }

    /**
     * Mark first login as completed
     */
    public function markAsFirstLoginCompleted(): void
    {
        $this->is_first_login = false;
        $this->temp_password_encrypted = null;
        $this->password_reset_token = null;
        $this->password_reset_token_expires_at = null;
        $this->save();
    }

    /**
     * Generate a password reset token
     */
    public function generatePasswordResetToken(): string
    {
        $this->password_reset_token = Str::random(60);
        $this->password_reset_token_expires_at = now()->addHours(24);
        $this->save();

        return $this->password_reset_token;
    }

    /**
     * Validate password reset token
     */
    public function isPasswordResetTokenValid(?string $token): bool
    {
        if (!$token || !$this->password_reset_token) {
            return false;
        }

        return hash_equals($this->password_reset_token, $token) &&
               $this->password_reset_token_expires_at &&
               $this->password_reset_token_expires_at->isFuture();
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken(): void
    {
        $this->password_reset_token = null;
        $this->password_reset_token_expires_at = null;
        $this->save();
    }
}
