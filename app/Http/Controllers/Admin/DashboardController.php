<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = MenuItem::count();
        $totalCategories = Category::count();
        $missingImages = MenuItem::query()
            ->whereNull('image_path')
            ->orWhere('image_path', '')
            ->with('category')
            ->orderBy('name')
            ->limit(20)
            ->get();

        $categoryBreakdown = Category::query()
            ->withCount('menuItems')
            ->orderBy('sort_order')
            ->get();

        return view('admin.dashboard', [
            'totalItems' => $totalItems,
            'totalCategories' => $totalCategories,
            'missingImages' => $missingImages,
            'categoryBreakdown' => $categoryBreakdown,
        ]);
    }
}
