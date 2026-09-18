<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id', 'item_key', 'name', 'name_ar', 'price',
        'badge', 'emoji', 'description', 'description_ar', 'image_path', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return str_starts_with($this->image_path, 'uploads/')
            ? asset('storage/' . $this->image_path)
            : asset($this->image_path);
    }
}
