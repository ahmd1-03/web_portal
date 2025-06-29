<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'external_link',
        'is_active'
    ];

    protected $casts = [
    'is_active' => 'boolean' // tambahkan ini
];
}
