<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'action',
        'title',
        'details',
        'user_id',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'deleted_at' => 'datetime',
        'timestamp' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = ['deleted_at', 'timestamp', 'created_at', 'updated_at'];

    /**
     * Boot method to auto-set timestamp
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->timestamp) {
                $model->timestamp = now();
            }
        });
    }

    /**
     * Scope for filtering by action type
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for active (non-deleted) activities
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope for deleted activities
     */
    public function scopeDeleted($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    /**
     * Scope for activities within the last year
     */
    public function scopeLastYear($query)
    {
        return $query->where('timestamp', '>=', now()->subYear());
    }
}
