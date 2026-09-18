<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Setting;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->with('menuItems')
            ->orderBy('sort_order')
            ->get();

        $settings = Setting::current();

        $heroImages = HeroSlide::orderBy('sort_order')
            ->get()
            ->map(fn ($slide) => $slide->imageUrl())
            ->values();

        $menuForJs = [];
        $categoryLabels = [];

        foreach ($categories as $category) {
            $categoryLabels[$category->key] = "{$category->title} · {$category->title_ar}";

            $menuForJs[$category->key] = $category->menuItems->where('is_active', true)->map(fn ($item) => [
                'id' => $item->item_key,
                'name' => $item->name,
                'ar' => $item->name_ar,
                'price' => (float) $item->price,
                'badge' => $item->badge,
                'emoji' => $item->emoji,
                'desc' => $item->description,
                'descAr' => $item->description_ar,
                'img' => $item->imageUrl(),
            ])->values();
        }

        return view('menu.index', [
            'categories' => $categories,
            'settings' => $settings,
            'heroImages' => $heroImages,
            'menuForJs' => $menuForJs,
            'categoryLabels' => $categoryLabels,
            'waNumberDigits' => preg_replace('/\D/', '', $settings->whatsapp_number),
        ]);
    }
}
