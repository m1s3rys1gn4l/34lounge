<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = ['sort_order', 'image_path'];

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
