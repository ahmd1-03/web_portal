<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_url',
        'external_link',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter cards by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If the value already starts with /storage/, return it as-is
        if (str_starts_with($value, '/storage/')) {
            return $value;
        }

        // Otherwise, generate the relative URL path
        return '/storage/' . $value;
    }
}
