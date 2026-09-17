<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 7) as $i) {
            HeroSlide::updateOrCreate(
                ['sort_order' => $i - 1],
                ['image_path' => "images/hero{$i}.jpg"]
            );
        }
    }
}
