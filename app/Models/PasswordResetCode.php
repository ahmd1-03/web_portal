<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    /**
     * Check if the code is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope to get valid codes (not expired)
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Find code by email and code
     */
    public static function findByEmailAndCode($email, $code)
    {
        return static::where('email', $email)
                    ->where('code', $code)
                    ->valid()
                    ->first();
    }
}
