<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'restaurant_name', 'whatsapp_number', 'location', 'currency',
        'show_arabic', 'show_placeholder', 'enable_popups', 'enable_whatsapp_order',
    ];

    protected $casts = [
        'show_arabic' => 'boolean',
        'show_placeholder' => 'boolean',
        'enable_popups' => 'boolean',
        'enable_whatsapp_order' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
