<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->get();

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:8192'],
        ]);

        if (str_starts_with($heroSlide->image_path, 'uploads/')) {
            Storage::disk('public')->delete($heroSlide->image_path);
        }

        $heroSlide->image_path = $request->file('image')->store('uploads/hero', 'public');
        $heroSlide->save();

        return redirect()->route('admin.hero-slides.index')->with('status', 'Slide ' . ($heroSlide->sort_order + 1) . ' image updated.');
    }
}
