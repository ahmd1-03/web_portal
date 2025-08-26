<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
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
}
